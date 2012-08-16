<?php

/* FXTracker: Home */

function BugTrackerHome()
{
	// Global some stuff
	global $smcFunc, $context, $user_info, $user_profile, $txt, $sourcedir, $modSettings, $scripturl;
	
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
				'issues' => 0,
				'features' => 0,
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
		' . $where . '
		ORDER BY id DESC'
	);

	// If we have zero or less(?), don't bother fetching them. 
	$context['bugtracker']['entries'] = array();
	$context['bugtracker']['feature'] = array();
	$context['bugtracker']['issue'] = array();
	$context['bugtracker']['attention'] = array();
	
	// Latest stuff
	$lnum_features = 0;
	$lnum_issues = 0;
	$latest_features = array();
	$latest_issues = array();
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

		// Also create a list of issues and features!
		$context['bugtracker'][$entry['type']][] = $context['bugtracker']['entries'][$entry['id']];

		// Is the status of this entry "attention"? If so, add it to the list of attention requirements thingies!
		if ($entry['attention'])
			$context['bugtracker']['attention'][] = $context['bugtracker']['entries'][$entry['id']];
			
		if (array_key_exists($entry['project'], $context['bugtracker']['projects']))
			$context['bugtracker']['entries'][$entry['id']]['project'] = $context['bugtracker']['projects'][$entry['project']];
			
		// What kind of entry is this?
		switch ($entry['type'])
		{
			case 'issue':
				if ($lnum_issues < 5 && !in_array($entry['status'], array('done', 'reject')))
				{
					$latest_issues[] = $context['bugtracker']['entries'][$entry['id']];
					$lnum_issues++;
				}
				if (array_key_exists($entry['project'], $context['bugtracker']['projects']) && !in_array($entry['status'], array('done', 'reject')))
					$context['bugtracker']['projects'][$entry['project']]['num']['issues']++;
				
				break;
			
			case 'feature':
				if ($lnum_features < 5 && !in_array($entry['status'], array('done', 'reject')))
				{
					$latest_features[] = $context['bugtracker']['entries'][$entry['id']];
					$lnum_features++;
				}
				if (array_key_exists($entry['project'], $context['bugtracker']['projects']) && !in_array($entry['status'], array('done', 'reject')))
					$context['bugtracker']['projects'][$entry['project']]['num']['features']++;
				
				break;
		}
	}

	// Clean up.
	$smcFunc['db_free_result']($request);

	// Put the last 5 entries of each category in a new array.
	$context['bugtracker']['latest']['issues'] = $latest_issues;
	$context['bugtracker']['latest']['features'] = $latest_features;
	
        // We're going to create a list for this.
        require_once($sourcedir . '/Subs-List.php');
	require_once($sourcedir . '/FXTracker/Subs-View.php');
	$listOptions = createListOptionsImportant($scripturl . '?action=bugtracker');

	// Create the list
	createList($listOptions);

	// What's our template, doc?
	$context['sub_template'] = 'TrackerHome';
}

?>