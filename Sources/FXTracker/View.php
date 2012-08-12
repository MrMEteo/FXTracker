<?php

/* FXTracker: View */

function BugTrackerView()
{
	// Our usual variables.
	global $context, $smcFunc, $user_info, $user_profile, $txt, $scripturl;

	// Grab the info for this issue, along with the project, and if we can't, tell the user that the issue does not exist.
	$request = $smcFunc['db_query']('', '
		SELECT
			e.id AS entry_id, e.name AS entry_name, e.description, e.type,
			e.tracker, e.private, e.startedon, e.project,
			e.status, e.attention, e.progress,
			p.id, p.name As project_name
		FROM {db_prefix}bugtracker_entries AS e
		INNER JOIN {db_prefix}bugtracker_projects AS p ON (e.project = p.id)
		WHERE e.id = {int:entry}',
		array(
			'entry' => $_GET['entry'],
		)
	);

	// Do we have anything? Or too much?
	if ($smcFunc['db_num_rows']($request) == 0 || $smcFunc['db_num_rows']($request) >= 2)
		fatal_lang_error('entry_no_exist');

	// Pick our data.
	$data = $smcFunc['db_fetch_assoc']($request);

	// Are we allowed to view private issues, and is this one of them?
	if ($data['tracker'] != $context['user']['id'] && (!allowedTo('bugtracker_viewprivate') && $data['private'] == 1))
		fatal_lang_error('entry_is_private', false);
		
	// Okay, load the template now.
	loadTemplate('fxt/View');

	// Load the data for the tracker.
	loadMemberData($data['tracker']);
	
	// Load every note associated with this entry...
	$result = $smcFunc['db_query']('', '
		SELECT
			id, authorid, time_posted,
			note
		FROM {db_prefix}bugtracker_notes
		WHERE entryid = {int:id}
		ORDER BY time_posted DESC',
		array(
			'id' => $data['entry_id'],
		)
	);
	
	// Fetch them.
	$notes = array();
	while ($note = $smcFunc['db_fetch_assoc']($result))
	{
		// Okay, we're not afraid to load the data of the tracker.
		loadMemberData($note['authorid']);
		
		// Then put this note together.
		$notes[] = array(
			'id' => $note['id'],
			'text' => parse_bbc($note['note']),
			'time' => timeformat($note['time_posted']),
			'user' => $user_profile[$note['authorid']],
		);
	}

	// Put the data in $context for the template!
	$context['bugtracker']['entry'] = array(
		'id' => $data['entry_id'],
		'name' => $data['entry_name'],
		'desc' => parse_bbc($smcFunc['htmlspecialchars']($data['description'])),
		'type' => $data['type'],
		'tracker' => $user_profile[$data['tracker']],
		'private' => $data['private'],
		'started' => $data['startedon'],
		'project' => array(
			'id' => (int) $data['project'],
			'name' => $data['project_name'],
		),
		'status' => $data['status'],
		'attention' => $data['attention'],
		'progress' => (empty($data['progress']) ? '0' : $data['progress']) . '%',
		'is_new' => isset($_GET['new']),
		'notes' => $notes,
	);

	// Stuff the linktree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=bugtracker;sa=projectindex;project=' . $context['bugtracker']['entry']['project']['id'],
		'name' => $data['project_name'],
	);
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=bugtracker;sa=view;entry=' . $data['entry_id'],
		'name' => sprintf($txt['entrytitle'], $data['entry_id'], $data['entry_name']),
	);

	// Setup permissions... Not just one of them!
        $own_any = array('mark', 'mark_new', 'mark_wip', 'mark_done', 'mark_reject', 'mark_attention', 'edit', 'remove', 'remove_note', 'edit_note', 'add_note');
        $is_own = $context['user']['id'] == $data['tracker'];
        foreach ($own_any as $perm)
        {
                $context['can_bt_' . $perm . '_any'] = allowedTo('bt_' . $perm . '_any');
                $context['can_bt_' . $perm . '_own'] = allowedTo('bt_' . $perm . '_own') && $is_own;
        }
	
	// If we can mark something.... tell us!
        $context['bt_can_mark'] = allowedTo(array('can_bt_mark_own', 'can_bt_mark_any')) && allowedTo(array('can_bt_mark_new_own', 'can_bt_mark_new_any', 'can_bt_mark_wip_own', 'can_bt_mark_wip_any', 'can_bt_mark_done_own', 'can_bt_mark_done_any', 'can_bt_mark_reject_own', 'can_bt_mark_reject_any'));
	

	// Set the title.
	$context['page_title'] = sprintf($txt['view_title'], $data['entry_id']);

	// Then tell SMF what template to load.
	$context['sub_template'] = 'TrackerView';
}

function BugTrackerViewProject()
{
	global $context, $smcFunc, $txt, $scripturl, $user_profile;

	// Load the project data.
	$result = $smcFunc['db_query']('', '
		SELECT
			id, name
		FROM {db_prefix}bugtracker_projects
		WHERE id = {int:project}',
		array(
			'project' => (int) $_GET['project'],
		)
	);

	// Got something?
	if ($smcFunc['db_num_rows']($result) == 0 || $smcFunc['db_num_rows']($result) > 1)
		fatal_lang_error('project_no_exist');

	// Fetch it!
	$pdata = $smcFunc['db_fetch_assoc']($result);
	
	// View closed, or rejected, or...both?
	$viewboth = isset($_GET['viewall']) || (isset($_GET['viewrejected']) && isset($_GET['viewclosed']));
	$viewclosed = isset($_GET['viewclosed']) || $viewboth;
	$viewrejected = isset($_GET['viewrejected']) || $viewboth;
	
	// Grab the entries.
	$where = 'project = {int:projectid}';
	if (!allowedTo('bugtracker_viewprivate'))
		$where .= ' AND private = 0';
	
	$result = $smcFunc['db_query']('', '
		SELECT
			id, name, description, type,
			status, progress, private,
			attention
		FROM {db_prefix}bugtracker_entries
		WHERE ' . $where . '
		ORDER BY id DESC',
		array(
			'projectid' => $pdata['id'],
		)
	);

	// If we've got none, too bad. Just start dammit.
	$closed = 0;
	$rejected = 0;
	$entries = array();
	$attention = array();
	while ($entry = $smcFunc['db_fetch_assoc']($result))
	{

		// Okay, if this entry is marked as closed and we aren't viewing closed entries, skip it.
		if ($entry['status'] == 'done' && !$viewclosed)
		{
			$closed++;
			continue;
		}
			
		if ($entry['status'] == 'reject' && !$viewrejected)
		{
			$rejected++;
			continue;
		}
	
		// Then we're ready for some action.
		$entries[$entry['id']] = array(
			'id' => $entry['id'],
			'name' => $entry['name'],
			'shortdesc' => shorten_subject($smcFunc['htmlspecialchars']($entry['description']), 50),
			'type' => $entry['type'],
			'private' => $entry['private'],
			'status' => $entry['status'],
			'attention' => $entry['attention'],
			'progress' => empty($entry['progress']) ? '0%' : $entry['progress'] . '%',
		);
		
		// Is the status of this entry "attention"? If so, add it to the list of attention requirements thingies!
		if ($entry['attention'])
			$attention[] = $entries[$entry['id']];
	}

	// Load the template.
	loadTemplate('fxt/ViewProject');
	
	// How many items are closed?
	$context['bugtracker']['num_closed'] = $closed;
	$context['bugtracker']['num_rejected'] = $rejected;
	
	// Viewing a category?
	$context['bugtracker']['view'] = array(
		'closed' => $viewclosed,
		'rejected' => $viewrejected,
		'link' => array(
			'closed' => $viewboth ? ';viewrejected' : ($viewrejected ? ';viewall' : ($viewclosed ? '' : ';viewclosed')),
			'rejected' => $viewboth ? ';viewclosed' : ($viewclosed ? ';viewall' : ($viewrejected ? '' : ';viewrejected')),
		),
	);

	// What do we have, from issues and such?
	$context['bugtracker']['entries'] = $entries;
	$context['bugtracker']['attention'] = $attention;
	$context['bugtracker']['project'] = $pdata;

	// Also stuff the linktree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=bugtracker;sa=projectindex;project=' . $context['bugtracker']['project']['id'],
		'name' => $context['bugtracker']['project']['name'],
	);
	
	// Page title time!
	$context['page_title'] = $context['bugtracker']['project']['name'];

	// Can we add new entries?
	$context['can_bt_add'] = allowedTo('bt_add');

	// And the sub template.
	$context['sub_template'] = 'TrackerViewProject';
}

function BugTrackerViewType()
{
	global $context, $smcFunc, $txt, $scripturl;

	// Start by checking if we are grabbing a valid type!
	$types = array('feature', 'issue');

	if (!in_array($_GET['type'], $types))
		fatal_lang_error('project_no_exist');
		
	// Load the template.
	loadTemplate('fxt/ViewType');

	// Okay, then start loading every entry.
	$private = !allowedTo('bugtracker_viewprivate') ? 'AND private="0"' : '';
	$result = $smcFunc['db_query']('', '
		SELECT
			e.id AS entry_id, e.name AS entry_name, e.description, e.type,
			e.tracker, e.private, e.startedon, e.project,
			e.status, e.attention, e.progress,
			p.id, p.name As project_name
		FROM {db_prefix}bugtracker_entries AS e
		INNER JOIN {db_prefix}bugtracker_projects AS p ON (e.project = p.id)
		WHERE e.type = {string:type}
		' . $private . '
		ORDER BY id DESC',
		array(
			'type' => $_GET['type'],
		)
	);

	// Fetch 'em!
	$closed = 0;
	$entries = array();
	$attention = array();
	while ($entry = $smcFunc['db_fetch_assoc']($result))
	{
		// Then we're ready for some action.
		$entries[$entry['entry_id']] = array(
			'id' => $entry['entry_id'],
			'name' => $entry['entry_name'],
			'shortdesc' => shorten_subject($smcFunc['htmlspecialchars']($entry['description']), 50),
			'type' => $entry['type'],
			'private' => ($entry['private'] == 1 ? true : false),
			'status' => $entry['status'],
			'attention' => $entry['attention'],
			'project' => array(
				'id' => $entry['id'],
				'name' => $entry['project_name'],
			),
               		'progress' => empty($entry['progress']) ? '0%' : $entry['progress'] . '%',
		);

		// Is the status of this entry "attention"? If so, add it to the list of attention requirements thingies!
		if ($entry['attention'])
			$attention[] = $entries[$entry['entry_id']];

		if ($entry['status'] == 'done')
			$closed++;
	}

	// So matey tell me what ya got. Wait no, tell $context!
	$context['bugtracker']['entries'] = $entries;
	$context['bugtracker']['attention'] = $attention;
	$context['bugtracker']['num_closed'] = $closed;
	$context['bugtracker']['viewtype_type'] = $_GET['type'];

	// Set up the linktree.
	$context['linktree'][] = array(
		'name' => sprintf($txt['view_all'], $txt['bugtracker_' . $_GET['type']]),
		'url' => $scripturl . '?action=bugtracker;sa=viewtype;type=' . $_GET['type'],
	);
	
	// Page title.
	$context['page_title'] = sprintf($txt['view_all'], $txt['bugtracker_' . $_GET['type']]);

	// And the sub-template.
	$context['sub_template'] = 'TrackerViewType';
	
}

function BugTrackerViewStatus()
{
	global $context, $smcFunc, $txt, $scripturl;

	// Start by checking if we are grabbing a valid type!
	$types = array('new', 'wip', 'done', 'reject');

	if (!in_array($_GET['status'], $types))
		fatal_lang_error('project_no_exist');
		
	// Load the template.
	loadTemplate('fxt/ViewType');

	// Okay, then start loading every entry.
	$private = !allowedTo('bugtracker_viewprivate') ? 'AND private="0"' : '';
	$result = $smcFunc['db_query']('', '
		SELECT
			e.id AS entry_id, e.name AS entry_name, e.description, e.type,
			e.tracker, e.private, e.startedon, e.project,
			e.status, e.attention, e.progress,
			p.id, p.name As project_name
		FROM {db_prefix}bugtracker_entries AS e
		INNER JOIN {db_prefix}bugtracker_projects AS p ON (e.project = p.id)
		WHERE e.status = {string:status}
		' . $private . '
		ORDER BY id DESC',
		array(
			'status' => $_GET['status'],
		)
	);

	// Fetch 'em!
	$closed = 0;
	$entries = array();
	$attention = array();
	while ($entry = $smcFunc['db_fetch_assoc']($result))
	{
		// Then we're ready for some action.
		$entries[$entry['entry_id']] = array(
			'id' => $entry['entry_id'],
			'name' => $entry['entry_name'],
			'shortdesc' => shorten_subject($smcFunc['htmlspecialchars']($entry['description']), 50),
			'type' => $entry['type'],
			'private' => ($entry['private'] == 1 ? true : false),
			'status' => $entry['status'],
			'attention' => $entry['attention'],
			'project' => array(
				'id' => $entry['id'],
				'name' => $entry['project_name'],
			),
               		'progress' => empty($entry['progress']) ? '0%' : $entry['progress'] . '%',
		);

		// Is the status of this entry "attention"? If so, add it to the list of attention requirements thingies!
		if ($entry['attention'])
			$attention[] = $entries[$entry['entry_id']];

		if ($entry['status'] == 'done')
			$closed++;
	}

	// So matey tell me what ya got. Wait no, tell $context!
	$context['bugtracker']['entries'] = $entries;
	$context['bugtracker']['attention'] = $attention;
	$context['bugtracker']['num_closed'] = $closed;

	// Set up the linktree.
	$context['linktree'][] = array(
		'name' => sprintf($txt['view_all'], $txt['status_' . $_GET['status']]),
		'url' => $scripturl . '?action=bugtracker;sa=viewtype;type=' . $_GET['status'],
	);
	
	// Page title.
	$context['page_title'] = sprintf($txt['view_all'], $txt['status_' . $_GET['status']]);

	// And the sub-template.
	$context['sub_template'] = 'TrackerViewType';
	
}

?>