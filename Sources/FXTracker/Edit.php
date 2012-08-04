<?php

/* FXTracker: Edit */

function BugTrackerEdit()
{
	global $context, $smcFunc, $txt, $sourcedir, $scripturl;

	// Are we using a valid entry id?
	$result = $smcFunc['db_query']('', '
		SELECT
			e.id AS entry_id, e.name AS entry_name, e.description, e.type,
			e.tracker, e.private, e.startedon, e.project, e.attention,
			e.status, e.attention, e.progress,
			p.id, p.name As project_name
		FROM {db_prefix}bugtracker_entries AS e
		INNER JOIN {db_prefix}bugtracker_projects AS p ON (e.project = p.id)
		WHERE e.id = {int:entry}',
		array(
			'entry' => $_GET['entry'],
		)
	);

	// No or multiple entries?
	if ($smcFunc['db_num_rows']($result) == 0 || $smcFunc['db_num_rows']($result) > 1)
		fatal_lang_error('entry_no_exist');

	// So we have just one...
	$entry = $smcFunc['db_fetch_assoc']($result);

	// Not ours, and we have no permission to edit someone else's entry?
	if (!allowedTo('bt_edit_any') && (allowedTo('bt_edit_own') && $context['user']['id'] != $entry['tracker']))
		fatal_lang_error('edit_entry_else_noaccess');

	// Or... It is private! I know!
	if ($entry['tracker'] != $context['user']['id'] && (!allowedTo('bugtracker_viewprivate') && $entry['private'] == 1))
		fatal_lang_error('entry_is_private', false);
		
	// Load the template...
	loadTemplate('fxt/Edit');

	// We want the default SMF WYSIWYG editor and Subs-Post.php to make stuff look SMF-ish.
	require_once($sourcedir . '/Subs-Editor.php');
	include($sourcedir . '/Subs-Post.php');
	
	// Do this...
	un_preparsecode($entry['description']);

	// Some settings for it...
	$editorOptions = array(
		'id' => 'entry_desc',
		'value' => $entry['description'],
		'height' => '175px',
		'width' => '100%',
		// XML preview.
		'preview_type' => 2,
	);
	create_control_richedit($editorOptions);

	// Store the ID. Might need it later on.
	$context['post_box_name'] = $editorOptions['id'];

	// Set up the context.
	$context['bugtracker']['entry'] = array(
		'id' => $entry['entry_id'],
		'name' => $entry['entry_name'],
		'type' => $entry['type'],
		'tracker' => $entry['tracker'],
		'private' => $entry['private'],
		'status' => $entry['status'],
		'progress' => (int) $entry['progress'],
		'attention' => $entry['attention'],
	);

	$context['page_title'] = $txt['entry_edit'];

	// Set up the linktree.
	$context['linktree'][] = array(
		'name' => $entry['project_name'],
		'url' => $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['id'] // Weird, but it IS the project ID!
	);
	// Even more...
	$context['linktree'][] = array(
		'name' => sprintf($txt['entry_edit_lt'], $entry['entry_name']),
		'url' => $scripturl . '?action=bugtracker;sa=edit;entry=', $entry['entry_id']
	);

	// And set the sub template.
	$context['sub_template'] = 'BugTrackerEdit';
}

function BugTrackerSubmitEdit()
{
	global $smcFunc, $context, $sourcedir, $scripturl;
	
	// Then, is the required is_fxt POST set?
	if (!isset($_POST['is_fxt']) || empty($_POST['is_fxt']))
		fatal_lang_error('save_failed');
		
	// Oh noes, no entry?
	if (!isset($_POST['entry_id']) || empty($_POST['entry_id']))
		fatal_lang_error('save_failed');
	
	// Load the tracker?
	$result = $smcFunc['db_query']('', '
		SELECT
			tracker, project, type
		FROM {db_prefix}bugtracker_entries
		WHERE id = {int:id}',
		array(
			'id' => $_POST['entry_id'],
		)
	);
	
	if ($smcFunc['db_num_rows']($result) == 0)
		fatal_lang_error('entry_no_exist', false);
		
	// What's our tracker?
	$extra = $smcFunc['db_fetch_assoc']($result);
	
	// Not ours, and we have no permission to edit someone else's entry?
	if (!allowedTo('bt_edit_any') && (allowedTo('bt_edit_own') && $context['user']['id'] != $extra['tracker']))
		fatal_lang_error('edit_entry_else_noaccess');
	
	// Load Subs-Post.php, will need that!
	include($sourcedir . '/Subs-Post.php');

	// Pour over these variables, so they can be altered and done with.
	$entry = array(
		'title' => $_POST['entry_title'],
		'type' => $_POST['entry_type'],
		'private' => !empty($_POST['entry_private']),
		'description' => $_POST['entry_desc'],
		'mark' => $_POST['entry_mark'],
		'attention' => !empty($_POST['entry_attention']),
		'progress' => $_POST['entry_progress'],
		'id' => $_POST['entry_id']
	);

	// Check if the title, the type or the description are empty.
	if (empty($entry['title']))
		fatal_lang_error('no_title', false);

	// Type...
	if (empty($entry['type']) || !in_array($entry['type'], array('issue', 'feature')))
		fatal_lang_error('no_type', false);

	// And description.
	if (empty($entry['description']))
		fatal_lang_error('no_description', false);

	// Are we submitting a valid mark? (rare condition)
	if (!in_array($entry['mark'], array('new', 'wip', 'done', 'reject')))
		fatal_lang_error('save_failed');
		
	// No entry?
	if (empty($entry['id']))
		fatal_lang_error('save_failed');

	// Preparse the message.
	preparsecode($smcFunc['htmlspecialchars']($entry['description']));

	// Okay, lets prepare the entry data itself! Create an array of the available types.
	$fentry = array(
		'title' => $smcFunc['htmlspecialchars']($entry['title']),
		'type' => strtolower($entry['type']),
		'private' => (int) $entry['private'],
		'description' => $entry['description'],
		'mark' => strtolower($entry['mark']),
		'attention' => (int) $entry['attention'],
		'progress' => (int) $_POST['entry_progress'],
		'id' => (int) $entry['id']
	);
	
	// Uhh, type changed?
	if ($fentry['type'] != $extra['type'])
	{
		// Okay, shouldn't be too advanced.
		$decrease = $extra['type'] == 'feature' ? 'feature' : 'issue';
		$increase = $extra['type'] == 'feature' ? 'issue' : 'feature';
		
		// Do it ffs!
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}bugtracker_projects
			SET
				' . $decrease . 'num = ' . $decrease . 'num-1,
				' . $increase . 'num = ' . $increase . 'num+1
			WHERE id = {int:pid}',
			array(
				'pid' => $extra['project']
			)
		);
	}

	// Assuming we have everything ready now, update!
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}bugtracker_entries
		SET
			name = {string:title},
			type = {string:type},
			private = {int:private},
			description = {string:description},
			status = {string:mark},
			attention = {int:attention},
			progress = {int:progress}
		WHERE id = {int:id}',
		array(
			'id' => $fentry['id'],
			'title' => $fentry['title'],
			'type' => $fentry['type'],
			'private' => $fentry['private'],
			'description' => $fentry['description'],
			'mark' => $fentry['mark'],
			'attention' => $fentry['attention'],
			'progress' => $fentry['progress']
		)
	);
	
	// Then we're ready to opt-out!
	redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $fentry['id']);
}

function BugTrackerMarkEntry()
{
	// Globalizing...
	global $context, $scripturl, $smcFunc;

	// Load data associated with this entry, if it exists.
	$result = $smcFunc['db_query']('', '
		SELECT 
			id, tracker, status, attention
		FROM {db_prefix}bugtracker_entries
		WHERE id = {int:entry}',
		array(
			'entry' => $_GET['entry'],
		)
	);

	// Got any?
	if ($smcFunc['db_num_rows']($result) == 0 || $smcFunc['db_num_rows']($result) > 1)
		fatal_lang_error('entry_no_exist');

	// Then fetch it.
	$data = $smcFunc['db_fetch_assoc']($result);

	// Then, are we allowed to do this kind of stuff?
	if (allowedTo('bt_mark_any') || (allowedTo('bt_mark_own') && $context['user']['id'] == $data['tracker']))
	{
		// A list of possible types.
		$types = array('new', 'wip', 'done', 'dead', 'reject', 'attention');

		// Allow people to integrate with this.
		call_integration_hook('bt_mark_types', $types);
		
		// Not in the list?
		if (!in_array($_GET['as'], $types))
			fatal_lang_error('entry_mark_failed');

		// Because I like peanuts.
		if ($_GET['as'] == 'dead')
			fatal_error('You killed my entry! Murderer!', false);

		// Are we resetting attention?
		if ($_GET['as'] == 'attention')
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}bugtracker_entries
				SET attention={int:attention}
				WHERE id={int:id}',
				array(
					'attention' => $data['attention'] ? 0 : 1,
					'id' => $data['id'],
				)
			);

			redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $data['id']);
		}

		// And 'nother hook for this...
		call_integration_hook('bt_mark', array(&$_GET['as']));

		// So it is. Mark it!
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}bugtracker_entries
			SET status={string:newstatus}
			WHERE id={int:id}',
			array(
				'newstatus' => $_GET['as'],
				'id' => $data['id'],
			));

		// And redirect us back.
		redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $data['id']);
	}
	else
		fatal_lang_error('entry_unable_mark');
}

?>