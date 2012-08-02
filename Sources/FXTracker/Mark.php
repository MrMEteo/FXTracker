<?php

/* FXTracker: Mark */

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