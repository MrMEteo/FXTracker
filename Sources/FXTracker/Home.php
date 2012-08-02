<?php

/* FXTracker: Home */

function BugTrackerHome()
{
	// Global some stuff
	global $smcFunc, $context, $user_info, $user_profile, $txt;
	
	// Load our Home template.
	loadTemplate('fxt/Home');

	// Set the page title.
	$context['page_title'] = $txt['bugtracker_index'];

	// Grab the projects.
	$request = $smcFunc['db_query']('', '
		SELECT
			id, name, description, issuenum, featurenum
		FROM {db_prefix}bugtracker_projects'
	);

	// Start empty...
	$context['bugtracker']['projects'] = array();
	while ($project = $smcFunc['db_fetch_assoc']($request))
	{
		$context['bugtracker']['projects'][$project['id']] = array(
			'id' => $project['id'],
			'name' => $project['name'],
			'num' => array(
				'issues' => (int) $project['issuenum'],
				'features' => (int) $project['featurenum'],
			),
			'description' => parse_bbc($project['description']),
			'entries' => array(),
		);
	}

	// Clean up.
	$smcFunc['db_free_result']($request);

	// Grab the entries we are allowed to view.
	$where = !allowedTo('bugtracker_viewprivate') ? 'WHERE private = 0' : '';
	$request = $smcFunc['db_query']('', '
		SELECT
			id, name, description, type,
			tracker, private, project,
			status, attention, progress

		FROM {db_prefix}bugtracker_entries
		' . $where
	);

	// If we have zero or less(?), don't bother fetching them. 
	$context['bugtracker']['entries'] = array();
	$context['bugtracker']['feature'] = array();
	$context['bugtracker']['issue'] = array();
	$context['bugtracker']['attention'] = array();
	while ($entry = $smcFunc['db_fetch_assoc']($request))
	{
		// Then we're ready for some action.
		$context['bugtracker']['entries'][$entry['id']] = array(
			'id' => $entry['id'],
			'name' => $entry['name'],
			'shortdesc' => shorten_subject($entry['description'], 50),
			'desc' => $entry['description'], // As there may be a LOT of entries, do *NOT* use parse_bbc() here!
			'type' => $entry['type'],
			'tracker' => $entry['tracker'], // Again, if there are a lot of entries, loading member data for everything may *horribly* slow down the place.
			'private' => $entry['private'], // Is a boolean anyway.
			'project' => array(),
			'status' => $entry['status'],
			'attention' => $entry['attention'],
			'progress' => (empty($entry['progress']) ? '0' : $entry['progress']) . '%'
		);

		$pid = $entry['project'];
		if (array_key_exists($pid, $context['bugtracker']['projects']))
			$context['bugtracker']['entries'][$entry['id']]['project'] = $context['bugtracker']['projects'][$pid];

		// Also create a list of issues and features!
		$context['bugtracker'][$entry['type']][] = $context['bugtracker']['entries'][$entry['id']];

		// Is the status of this entry "attention"? If so, add it to the list of attention requirements thingies!
		if ($entry['attention'])
			$context['bugtracker']['attention'][] = $context['bugtracker']['entries'][$entry['id']];
			
		// Is this entry solved, maybe?
		if ($entry['status'] == 'done')
		{
			$type_dec = $entry['type'] == 'issue' ? 'issues' : 'features';
			$context['bugtracker']['projects'][$pid]['num'][$type_dec] - 1;
		}
	}

	// Clean up.
	$smcFunc['db_free_result']($request);

	// Put the last 5 entries of each category in a new array.
	$context['bugtracker']['latest']['issues'] = array_reverse(array_slice($context['bugtracker']['issue'], -5));
	$context['bugtracker']['latest']['features'] = array_reverse(array_slice($context['bugtracker']['feature'], -5));

	// What's our template, doc?
	$context['sub_template'] = 'TrackerHome';
}

?>