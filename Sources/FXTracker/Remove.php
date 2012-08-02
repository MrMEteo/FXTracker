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
		
		// And redirect back to the project index.
		redirectexit($scripturl . '?action=bugtracker;sa=projectindex;project=' . $data['project']);
	}
	else
		fatal_lang_error('remove_entry_noaccess', false);
}

?>