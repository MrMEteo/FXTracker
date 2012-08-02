<?php

/* FXTracker: Add */

function BugTrackerNewEntry()
{
	global $context, $smcFunc, $txt, $scripturl, $sourcedir;

	// Are we allowed to create new entries?
	isAllowedTo('bt_add');

	// Load the project data.
	$result = $smcFunc['db_query']('', '
		SELECT
			id, name
		FROM {db_prefix}bugtracker_projects
		WHERE id = {int:project}',
		array(
			'project' => $_GET['project']
		)
	);

	// Wait.... There is no project like this? Or there's more with the *same* ID? :O
	if ($smcFunc['db_num_rows']($result) == 0 || $smcFunc['db_num_rows']($result) > 1)
		fatal_lang_error('project_no_exist');
		
	// Load the template for this.
	loadTemplate('fxt/AddNew');

	// So we have just one...
	$project = $smcFunc['db_fetch_assoc']($result);

	// Validate the stuff.
	$context['bugtracker']['project'] = array(
		'id' => (int) $project['id'],
		'name' => $project['name']
	);

	// We want the default SMF WYSIWYG editor.
	require_once($sourcedir . '/Subs-Editor.php');

	// Some settings for it...
	$editorOptions = array(
		'id' => 'entry_desc',
		'value' => '',
		'height' => '175px',
		'width' => '100%',
		// XML preview.
		'preview_type' => 2,
	);
	create_control_richedit($editorOptions);

	// Store the ID.
	$context['post_box_name'] = $editorOptions['id'];
	
	// Setup the page title...
	$context['page_title'] = $txt['entry_add'];

	// Set up the linktree, too...
	$context['linktree'][] = array(
		'name' => $project['name'],
		'url' => $scripturl . '?action=bugtracker;sa=projectindex;project=' . $project['id']
	);
	$context['linktree'][] = array(
		'name' => $txt['entry_add'],
		'url' => $scripturl . '?action=bugtracker;sa=new;project=' . $project['id']
	);

	// Then, set what template we should use!
	$context['sub_template'] = 'BugTrackerAddNew';
}

function BugTrackerSubmitNewEntry()
{
	global $smcFunc, $context, $sourcedir, $scripturl;

	// Start with checking if we can add new stuff...
	isAllowedTo('bt_add');

	// Load Subs-Post.php, will need that!
	include($sourcedir . '/Subs-Post.php');

	// Then, is the required is_fxt POST set?
	if (!isset($_POST['is_fxt']) || empty($_POST['is_fxt']))
		fatal_lang_error('save_failed');

	// Pour over these variables, so they can be altered and done with.
	$entry = array(
		'title' => $_POST['entry_title'],
		'type' => $_POST['entry_type'],
		'private' => !empty($_POST['entry_private']),
		'description' => $_POST['entry_desc'],
		'mark' => $_POST['entry_mark'],
		'attention' => !empty($_POST['entry_attention']),
		'progress' => $_POST['entry_progress'],
		'project' => $_POST['entry_projectid']
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

	// Check if the project exists.
	$result = $smcFunc['db_query']('', '
		SELECT
			id
		FROM {db_prefix}bugtracker_projects
		WHERE id = {int:project}',
		array(
			'project' => $entry['project'],
		)
	);

	// The "real" check ;)
	if ($smcFunc['db_num_rows']($result) == 0 || $smcFunc['db_num_rows']($result) > 1)
		fatal_lang_error('project_no_exist');

	// Preparse the message.
	preparsecode($smcFunc['htmlspecialchars']($entry['description']));

	// Okay, lets prepare the entry data itself! Create an array of the available types.
	$fentry = array(
		'title' => $smcFunc['htmlspecialchars']($entry['title']),
		'type' => strtolower($entry['type']),
		'private' => (int) $entry['private'],
		'description' => $entry['description'], // No htmlspecialchars here because it'll fail to parse <br />s correctly!
		'mark' => strtolower($entry['mark']),
		'attention' => (int) $entry['attention'],
		'progress' => (int) $entry['progress'],
		'project' => (int) $entry['project'],
	);

	// Assuming we have everything ready now, lets do this! Insert this stuff first.
	$smcFunc['db_insert']('insert',
		'{db_prefix}bugtracker_entries',
		array(
			'name' => 'string',
			'description' => 'string',
			'type' => 'string',
			'tracker' => 'int',
			'private' => 'int',
			'project' => 'int',
			'status' => 'string',
			'attention' => 'int',
			'progress' => 'int'
		),
		array(
			$fentry['title'],
			$fentry['description'],
			$fentry['type'],
			$context['user']['id'],
			$fentry['private'],
			$fentry['project'],
			$fentry['mark'],
			$fentry['attention'],
			$fentry['progress']
		)
	);
			
	// Grab the ID of the entry just inserted.
	$entryid = $smcFunc['db_insert_id']('{db_prefix}bugtracker_entries', 'id');

	// What type is this again?
	$type = $fentry['type'] == 'issue' ? 'issue' : 'feature'; // In case this gets changed later on!
	
	// Then update the count at the projects.
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}bugtracker_projects
		SET ' . $type . 'num=' . $type . 'num+1
		WHERE id = {int:project}', 
		array(
			'project' => $fentry['project'],
		)
	);
	
	// Then we're ready to opt-out!
	redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $entryid . ';new');
}

?>