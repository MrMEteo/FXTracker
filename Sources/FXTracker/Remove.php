<?php

/* FXTracker: Remove */

function BugTrackerRemoveEntry()
{
	// TODO: Make this work with a trash can.
	global $context, $smcFunc, $scripturl;
	
	if (empty($_GET['entry']))
		fatal_lang_error('entry_no_exist');
	
	// Then try to load the issue data.
	$result = $smcFunc['db_query']('', '
		SELECT 
			id, name, project, tracker, type
		FROM {db_prefix}bugtracker_entries
		WHERE id = {int:entry}',
		array(
			'entry' => (int) $_GET['entry'],
		)
	);

	// None? Or more then one?
	if ($smcFunc['db_num_rows']($result) == 0 || $smcFunc['db_num_rows']($result) > 1)
		fatal_lang_error('entry_no_exist');

	// Fetch the data.
	$data = $smcFunc['db_fetch_assoc']($result);
        $smcFunc['db_free_result']($result);

	// Hmm, okay. Are we allowed to remove this entry?
	if (allowedTo('bt_remove_any') || (allowedTo('bt_remove_own') && $context['user']['id'] == $data['tracker']))
	{
		// Remove it ASAP.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}bugtracker_entries
			WHERE id = {int:id}',
			array(
				'id' => $data['id'],
			)
		);

		// And count one down from the project.
		$type = $data['type'] == 'issue' ? 'issue' : 'feature';
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}bugtracker_projects
			SET ' . $type . 'num = ' . $type . 'num-1
			WHERE id = {int:pid}',
			array(
				'pid' => $data['project'],
			)
		);
                
                // Also remove any notes left for this entry.
                $smcFunc['db_query']('', '
                        DELETE FROM {db_prefix}bugtracker_notes
                        WHERE entryid = {int:entry}',
                        array(
                              'entry' => $data['id']
                        )
                );
		
		// And redirect back to the project index.
		redirectexit($scripturl . '?action=bugtracker;sa=projectindex;project=' . $data['project']);
	}
	else
		fatal_lang_error('remove_entry_noaccess', false);
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
        $smcFunc['db_free_result']($result);        
        
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

?>