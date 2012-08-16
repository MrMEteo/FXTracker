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
	$smcFunc['db_free_result']($result);

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
		
	$smcFunc['db_free_result']($result);

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
			'progress' => 'int',
			'startedon' => 'int'
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
			$fentry['progress'],
			time()
		),
		// No idea why I need this but oh well! :D
		array()
	);
			
	// Grab the ID of the entry just inserted.
	$entryid = $smcFunc['db_insert_id']('{db_prefix}bugtracker_entries', 'id');
	
	// Then we're ready to opt-out!
	redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $entryid . ';new');
}

function BugTrackerAddNote()
{
        global $context, $smcFunc, $sourcedir, $txt, $scripturl;
        
        // Is the entry set?
        if (empty($_GET['entry']))
                fatal_lang_error('entry_no_exist', false);
        
        // Grab this entry, check if it exists.
        $result = $smcFunc['db_query']('', '
                SELECT
                        id, name, tracker
                FROM {db_prefix}bugtracker_entries
                WHERE id = {int:id}',
                array(
                        'id' => $_GET['entry'],
                )
        );
        
        // No entry? No note either!!
        if ($smcFunc['db_num_rows']($result) == 0)
                fatal_lang_error('entry_no_exists', false);
                
        // Data fetching, please.
        $data = $smcFunc['db_fetch_assoc']($result);
	$smcFunc['db_free_result']($result);
        
        // Are we, like, allowed to add notes to any entry or just our own?
        if (!allowedTo('bt_add_note_any') && (allowedTo('bt_add_note_own') && $context['user']['id'] != $data['tracker']))
                fatal_lang_error('cannot_add_note', false);
        
        // Okay. Set up the $context variable.
        $context['bugtracker']['note'] = array(
                'id' => $data['id'],
                'name' => $data['name'],
        );
        
        // We want the default SMF WYSIWYG editor and Subs-Post.php to make stuff look SMF-ish.
        require_once($sourcedir . '/Subs-Editor.php');
                
        // Some settings for it...
        $editorOptions = array(
                'id' => 'note_text',
                'value' => '',
                'height' => '175px',
                'width' => '100%',
                // XML preview.
                'preview_type' => 2,
        );
        create_control_richedit($editorOptions);
        
        // Store the ID. Might need it later on.
	$context['post_box_name'] = $editorOptions['id'];
        
        // Page title, too.
        $context['page_title'] = $txt['add_note'];
        
        // And the linktree, of course.
        $context['linktree'][] = array(
                'name' => $txt['add_note'],
                'url' => $scripturl . '?action=bugtracker;sa=addnote;entry=' . $data['id'],
        );
        
        // Set the sub template.
        loadTemplate('fxt/Notes');
        $context['sub_template'] = 'TrackerAddNote';
}

function BugTrackerAddNote2()
{
        global $context, $smcFunc, $sourcedir, $scripturl, $txt;
        
        // Okay. See if we have submitted the data!
        if (!isset($_POST['is_fxt']) || $_POST['is_fxt'] != true)
                fatal_lang_error('note_save_failed');
                
	// Oh noes, no entry?
	if (!isset($_POST['entry_id']) || empty($_POST['entry_id']))
		fatal_lang_error('note_save_failed');
                
        // Description empty?
        if (empty($_POST['note_text']))
                fatal_lang_error('note_empty');
                
        $note = array(
                'id' => $_POST['entry_id'],
                'note' => $_POST['note_text'],
        );
                
        // Try to load the entry.
        $result = $smcFunc['db_query']('', '
                SELECT
                        id, name, tracker
                FROM {db_prefix}bugtracker_entries
                WHERE id = {int:id}',
                array(
                        'id' => $note['id'],
                )
        );
        
        // None? :(
        if ($smcFunc['db_num_rows']($result) == 0)
                fatal_lang_error('entry_no_exist');
                
        // Then, fetch the data.
        $data = $smcFunc['db_fetch_assoc']($result);
	$smcFunc['db_free_result']($result);
        
        // Are we allowed to add notes to any entry or just our own?
        if (!allowedTo('bt_add_note_any') && (allowedTo('bt_add_note_own') && $context['user']['id'] != $data['tracker']))
                fatal_lang_error('cannot_add_note', false);
                
        // Need Subs-Post.php
        include($sourcedir . '/Subs-Post.php');
        
        // Then, preparse the note.
        preparsecode($smcFunc['htmlspecialchars']($note['note']));
        
        // And save!
        $smcFunc['db_insert']('insert',
		'{db_prefix}bugtracker_notes',
		array(
			'authorid' => 'int',
			'entryid' => 'int',
			'time_posted' => 'int',
                        'note' => 'string'
		),
		array(
			$context['user']['id'],
			$note['id'],
			time(),
			$note['note']
		),
		// No idea why I need this but oh well! :D
		array()
	);
        
        // PM the author of the entry...if it wasn't him/her that posted it.
        if ($context['user']['id'] != $data['tracker'])
        {
                $url1 = $scripturl . '?action=profile;u=' . $context['user']['id'];
                $url2 = $scripturl . '?action=bugtracker;sa=view;entry=' . $note['id'];
                $url3 = $url2 . '#note_' . $smcFunc['db_insert_id']('{db_prefix}bugtracker_notes', 'id');
		
		if ($context['user']['is_guest'])
			$text = sprintf($txt['note_pm_message_guest'], $url2, $url3);
		else
			$text = sprintf($txt['note_pm_message'], $url1, $context['user']['name'], $url2, $url3);
                sendpm(
                        array(
                              'bcc' => array(),
                              'to' => array($data['tracker'])
                        ),
                        $txt['note_pm_subject'],
                        $text,
                        false,
                        array(
                              'id' => 0,
                              'name' => $txt['note_pm_username'],
                              'username' => $txt['note_pm_username']
                        )
                );
        }
        
        // And done!
        redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $note['id']);
}

?>