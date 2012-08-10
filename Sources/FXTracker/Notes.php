<?php

/* FXTracker: Notes */

function BugTrackerAddNote()
{
        global $context, $smcFunc, $sourcedir;
        
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
        
        // Set the sub template.
        loadTemplate('fxt/Notes');
        $context['sub_template'] = 'TrackerAddNote';
}

function BugTrackerAddNote2()
{
        global $context, $smcFunc, $sourcedir, $scripturl;
        
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
        
        // And done!
        redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $note['id']);
}

function BugTrackerEditNote()
{
        // Need some stuff.
        global $context, $smcFunc, $user_profile, $sourcedir, $txt, $scripturl;
        
        // Try to grab the note.
        $result = $smcFunc['db_query']('', '
                SELECT
                        id, authorid, entryid,
                        note, time_posted
                FROM {db_prefix}bugtracker_notes
                WHERE id = {int:id}',
                array(
                        'id' => $_GET['note']
                )
        );
        
        if ($smcFunc['db_num_rows']($result) == 0)
                fatal_lang_error('note_no_exist');
                
        // Load the note itself
        $data = $smcFunc['db_fetch_assoc']($result);
        
        // Are we allowed to edit this note?
        if (allowedTo('bt_edit_note_any') || (allowedTo('bt_edit_note_own') && $data['authorid'] == $context['user']['id']))
        {
                loadMemberData($data['authorid']);
                
                // We want the default SMF WYSIWYG editor and Subs-Post.php to make stuff look SMF-ish.
                require_once($sourcedir . '/Subs-Editor.php');
                include($sourcedir . '/Subs-Post.php');
                
                // Do this...
                un_preparsecode($data['note']);
                
                // Some settings for it...
                $editorOptions = array(
                        'id' => 'note_text',
                        'value' => $data['note'],
                        'height' => '175px',
                        'width' => '100%',
                        // XML preview.
                        'preview_type' => 2,
                );
                create_control_richedit($editorOptions);
        
                // Store the ID. Might need it later on.
                $context['post_box_name'] = $editorOptions['id'];
                
                // Okay, lets set it up.
                $context['bugtracker']['note'] = array(
                        'id' => $data['id'],
                        'author' => $user_profile[$data['authorid']],
                        'time' => $data['time_posted'],
                        'note' => $data['note'],
                );
                
                // And the sub-template...
                loadTemplate('fxt/Notes');
                $context['sub_template'] = 'TrackerEditNote';
                
                // And built on the link tree.
                $context['linktree'][] = array(
                        'name' => $txt['edit_note'],
                        'url' => $scripturl . '?action=bugtracker;sa=editnote;note=' . $data['id'],
                );
        }
        else
                fatal_lang_error('note_edit_notyours');
}

function BugTrackerEditNote2()
{
        global $context, $smcFunc, $sourcedir;
        
        // Okay. See if we have submitted the data!
        if (!isset($_POST['is_fxt']) || $_POST['is_fxt'] != true)
                fatal_lang_error('note_save_failed');
                
        // Missing some data? :S
        if (empty($_POST['note_id']))
                fatal_lang_error('note_save_failed');
                
        if (empty($_POST['note_text']))
                fatal_lang_error('note_empty');
                
        // So we have submitted something. Grab the data to here.
        $pnote = array(
                'id' => $_POST['note_id'],
                'text' => $_POST['note_text'],
        );
        
        // Load the note data.
        $result = $smcFunc['db_query']('', '
                SELECT
                        id, entryid, authorid
                FROM {db_prefix}bugtracker_notes
                WHERE id = {int:id}',
                array(
                        'id' => $pnote['id'],
                )
        );
        
        // No note? :(
        if ($smcFunc['db_num_rows']($result) == 0)
                fatal_lang_error('note_no_exist');
                
        // Then grab the note.
        $tnote = $smcFunc['db_fetch_assoc']($result);
        
        // Not allowed to edit *this* note?
        if (!allowedTo('bt_edit_note_any') && (allowedTo('bt_edit_note_own') && $context['user']['id'] != $tnote['authorid']))
                fatal_lang_error('note_edit_notyours');
        
        // Need Subs-Post.php
        include($sourcedir . '/Subs-Post.php');
        
        // Preparse the message.
        preparsecode($smcFunc['htmlspecialchars']($pnote['text']));
        
        // And save it...
        $smcFunc['db_query']('', '
                UPDATE {db_prefix}bugtracker_notes
                SET note = {string:note}
                WHERE id = {int:id}',
                array(
                        'id' => $tnote['id'],
                        'note' => $pnote['text'],
                )
        );
        
        redirectexit($scripturl . 'action=bugtracker;sa=view;entry=' . $tnote['entryid']);
}

function BugTrackerRemoveNote()
{
        global $smcFunc, $context, $scripturl;
    
        // Try to grab the note...
        $result = $smcFunc['db_query']('', '
                SELECT
                        id, authorid, entryid
                FROM {db_prefix}bugtracker_notes
                WHERE id = {int:noteid}',
                array(
                        'noteid' => $_GET['note'],
                )
        );
        
        // None? That sucks...
        if ($smcFunc['db_num_rows']($result) == 0)
                fatal_lang_error('note_delete_failed');
                
        // Check if we can remove it -- wait, we need the data for that.
        $note = $smcFunc['db_fetch_assoc']($result);
        
        // Check if we can remove it, now.
        if (allowedTo('bt_remove_note_any') || (allowedTo('bt_remove_note_own') && $context['user']['id'] == $note['authorid']))
        {
                // Say bye to your note... *sniff*
                $smcFunc['db_query']('', '
                        DELETE
                                FROM {db_prefix}bugtracker_notes
                        WHERE id = {int:id}',
                        array(
                                'id' => $note['id'],
                        )
                );
                
                // And redirect back to the entry.
                redirectexit($scripturl . '?action=bugtracker;sa=view;entry=' . $note['entryid']);
        }
        else
                fatal_lang_error('note_delete_notyours');
}