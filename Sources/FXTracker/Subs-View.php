<?php

function viewGetEntries($start, $items_per_page, $sort, $where = '', $hideRejectClosed = array())
{
	global $context, $smcFunc, $settings, $scripturl, $txt, $user_profile;

	$private = !allowedTo('bugtracker_viewprivate') ? 'WHERE private = 0' : '';
	$fwhere = !empty($private) ? $private : '';
	if (!empty($where))
		if (!empty($fwhere))
			$fwhere .= ' AND ' . $where;
		else
			$fwhere = 'WHERE ' . $where;
		
	// Viewing rejected entries or resolved ones?
	if (!empty($hideRejectClosed) && is_array($hideRejectClosed))
	{
		if (in_array('reject', $hideRejectClosed))
			if (empty($fwhere))
				$fwhere = 'WHERE status != \'reject\'';
			else
				$fwhere .= ' AND status != \'reject\'';
		if (in_array('closed', $hideRejectClosed))
			if (empty($fwhere))
				$fwhere = 'WHERE status != \'done\'';
			else
				$fwhere .= ' AND status != \'done\'';
	}
		
	$result = $smcFunc['db_query']('', '
		SELECT
			id, name, description, type,
			tracker, private, project,
			status, attention, progress,
			startedon
		FROM {db_prefix}bugtracker_entries
		' . $fwhere . '
		ORDER BY ' . $sort . '
		LIMIT ' . $start . ', ' . $items_per_page
	);
	
	// Fetch 'em.
	$important_entries = array();
	while ($entry = $smcFunc['db_fetch_assoc']($result))
	{
		// Try to load the member data. Sorry if we can't grab you, but you're counted as Guest then.
		if (!loadMemberData($entry['tracker']))
			$user_link = '';
		else
			$user_link = $scripturl . '?action=profile;u=' . $user_profile[$entry['tracker']]['id_member'];
		
		// The image of the type.
		switch ($entry['type'])
		{
			default:
				$typeimg = $entry['type'] . '.png';
				
				break;
		}
		$typeimgsrc = '<img src="' . $settings['images_url'] . '/bugtracker/' . $typeimg . '" alt="" />';
		
		// And the status.
		switch ($entry['status'])
		{
			case 'wip':
				$statusimg = 'wip.gif';
				
				break;
			default:
				$statusimg = $entry['status'] . '.png';
				
				break;
		}
		$attention = $entry['attention'] ? '<img src="' . $settings['images_url'] . '/bugtracker/attention.png" alt="" />' : '';
		$statusimgsrc = $attention . '<img src="' . $settings['images_url'] . '/bugtracker/' . $statusimg . '" alt="" />';
		
		if (array_key_exists($entry['project'], $context['bugtracker']['projects']))
			$projecturl = '
			<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $context['bugtracker']['projects'][$entry['project']]['id'] . '">
				' . $context['bugtracker']['projects'][$entry['project']]['name'] . '
			</a>';
		else
			$projecturl = $txt['na'];
		
		$important_entries[] = array(
			'id' => $entry['id'],
			'typeimg' => $typeimgsrc,
			'statusimg' => $statusimgsrc,
			'name' => '
			<a href="' . $scripturl . '?action=bugtracker;sa=view;entry=' . $entry['id'] . '">
				' . $entry['name'] . ' ' . ($entry['status'] == 'wip' ? '<span class="smalltext" style="color:#E00000">(' . $entry['progress'] . '%)</span>' : '') . '
			</a>
			<div class="smalltext">
				' . ($user_profile[$entry['tracker']]['id_member'] == 0 ? sprintf($txt['tracked_by_guest'], timeformat($entry['startedon'])) : sprintf($txt['tracked_by_user'], timeformat($entry['startedon']), $user_link, $user_profile[$entry['tracker']]['member_name'])) . '
			</div>',
			
			'statusurl' => '
			<a href="' . $scripturl . '?action=bugtracker;sa=viewstatus;status=' . $entry['status'] . '">
				' . $txt['status_' . $entry['status']] . '
			</a>',
			
			'typeurl' => '
			<a href="' . $scripturl . '?action=bugtracker;sa=viewtype;type=' . $entry['type'] . '">
				' . $txt['bugtracker_' . $entry['type']] . '
			</a>',
			
			'projecturl' => $projecturl,
		);
	}
	
	$smcFunc['db_free_result']($result);
	
	return $important_entries;
}

function viewGetEntriesCount($hideRejectClosed = array(), $where = '')
{
	global $smcFunc;
	
	$private = !allowedTo('bugtracker_viewprivate') ? 'WHERE private = 0' : '';
	$fwhere = !empty($private) ? $private : '';
	if (!empty($where))
		if (!empty($fwhere))
			$fwhere .= ' AND ' . $where;
		else
			$fwhere = 'WHERE ' . $where;
	
	// Viewing rejected entries or resolved ones?
	if (!empty($hideRejectClosed) && is_array($hideRejectClosed))
	{
		if (in_array('reject', $hideRejectClosed))
			if (empty($fwhere))
				$fwhere = 'WHERE status != \'reject\'';
			else
				$fwhere .= ' AND status != \'reject\'';
		if (in_array('closed', $hideRejectClosed))
			if (empty($fwhere))
				$fwhere = 'WHERE status != \'done\'';
			else
				$fwhere .= ' AND status != \'done\'';
	}
	
	// Just do it.
	$result = $smcFunc['db_query']('', '
		SELECT count(id)
		FROM {db_prefix}bugtracker_entries
		' . $fwhere
	);
	
	list ($count) = $smcFunc['db_fetch_row']($result);
	
	$smcFunc['db_free_result']($result);
	
	return $count;
}

function viewGetImportant($start, $items_per_page, $sort, $where = '')
{
	global $context, $smcFunc, $settings, $scripturl, $txt, $user_profile;

	$private = !allowedTo('bugtracker_viewprivate') ? 'WHERE private = 0' : '';
	$fwhere = !empty($private) ? $private . ' AND attention = 1' : 'WHERE attention = 1';
	if (!empty($where))
		if (!empty($fwhere))
			$fwhere .= ' AND ' . $where;
		else
			$fwhere = 'WHERE ' . $where;
		
	$result = $smcFunc['db_query']('', '
		SELECT
			id, name, description, type,
			tracker, private, project,
			status, attention, progress,
			startedon
		FROM {db_prefix}bugtracker_entries
		' . $fwhere . '
		ORDER BY ' . $sort . '
		LIMIT ' . $start . ', ' . $items_per_page
	);
	
	// Fetch 'em.
	$important_entries = array();
	while ($entry = $smcFunc['db_fetch_assoc']($result))
	{
		// Try to load the member data. Sorry if we can't grab you, but you're counted as Guest then.
		if (!loadMemberData($entry['tracker']))
			$user_link = '';
		else
			$user_link = $scripturl . '?action=profile;u=' . $user_profile[$entry['tracker']]['id_member'];
		
		// The image of the type.
		switch ($entry['type'])
		{
			default:
				$typeimg = $entry['type'] . '.png';
				
				break;
		}
		$typeimgsrc = '<img src="' . $settings['images_url'] . '/bugtracker/' . $typeimg . '" alt="" />';
		
		// And the status.
		switch ($entry['status'])
		{
			case 'wip':
				$statusimg = 'wip.gif';
				
				break;
			default:
				$statusimg = $entry['status'] . '.png';
				
				break;
		}
		$statusimgsrc = '<img src="' . $settings['images_url'] . '/bugtracker/' . $statusimg . '" alt="" />';
		
		if (array_key_exists($entry['project'], $context['bugtracker']['projects']))
			$projecturl = '
			<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $context['bugtracker']['projects'][$entry['project']]['id'] . '">
				' . $context['bugtracker']['projects'][$entry['project']]['name'] . '
			</a>';
		else
			$projecturl = $txt['na'];
		
		$important_entries[] = array(
			'id' => $entry['id'],
			'typeimg' => $typeimgsrc,
			'statusimg' => $statusimgsrc,
			'name' => '
			<a href="' . $scripturl . '?action=bugtracker;sa=view;entry=' . $entry['id'] . '">
				' . $entry['name'] . ' ' . ($entry['status'] == 'wip' ? '<span class="smalltext" style="color:#E00000">(' . $entry['progress'] . '%)</span>' : '') . '
			</a>
			<div class="smalltext">
				' . ($user_profile[$entry['tracker']]['id_member'] == 0 ? sprintf($txt['tracked_by_guest'], timeformat($entry['startedon'])) : sprintf($txt['tracked_by_user'], timeformat($entry['startedon']), $user_link, $user_profile[$entry['tracker']]['member_name'])) . '
			</div>',
			
			'statusurl' => '
			<a href="' . $scripturl . '?action=bugtracker;sa=viewstatus;status=' . $entry['status'] . '">
				' . $txt['status_' . $entry['status']] . '
			</a>',
			
			'typeurl' => '
			<a href="' . $scripturl . '?action=bugtracker;sa=viewtype;type=' . $entry['type'] . '">
				' . $txt['bugtracker_' . $entry['type']] . '
			</a>',
			
			'projecturl' => $projecturl,
		);
	}
	
	$smcFunc['db_free_result']($result);
	
	return $important_entries;
}

function viewGetImportantCount($where = '')
{
	global $smcFunc;
	
	$private = !allowedTo('bugtracker_viewprivate') ? 'WHERE private = 0' : '';
	$fwhere = !empty($private) ? $private . ' AND attention = 1' : 'WHERE attention = 1';
	if (!empty($where))
		if (!empty($fwhere))
			$fwhere .= ' AND ' . $where;
		else
			$fwhere = 'WHERE ' . $where;
	
	// Just do it.
	$result = $smcFunc['db_query']('', '
		SELECT count(id)
		FROM {db_prefix}bugtracker_entries
		' . $fwhere
	);
	
	list ($count) = $smcFunc['db_fetch_row']($result);
	
	$smcFunc['db_free_result']($result);
	
	return $count;
}

function grabProjects($specific = '')
{
	global $smcFunc;
	
	$where = '';
	if (!empty($specific) && is_numeric($specific))
		$where = 'WHERE id = ' . $specific;
	
	$result = $smcFunc['db_query']('', '
		SELECT
			id, name, issuenum, featurenum
		FROM {db_prefix}bugtracker_projects
		' . $where
	);
	
	$projects = array();
	while ($project = $smcFunc['db_fetch_assoc']($result))
	{
		$projects[$project['id']] = $project;
	}
		
	$smcFunc['db_free_result']($result);
	
	// Anything specific, sir?
	if (!empty($specific) && isset($projects[$specific]))
		return $projects[$specific];
	else	
		return $projects;
}

function createListOptionsNormal($basehref, $where = '', $hideRejectClosed = true)
{
	global $context, $txt, $modSettings;
			
	$listOptions = array(
		'id' => 'fxt_view',
		'items_per_page' => $modSettings['defaultMaxMessages'],
		'no_items_label' => $txt['no_items_attention'],
		'base_href' => $basehref,
		'default_sort_col' => 'id',
		'default_sort_dir' => 'desc',
		'start_var_name' => 'mainstart',
		'request_vars' => array(
			'desc' => 'maindesc',
			'sort' => 'mainsort',
		),
		'get_items' => array(
			'function' => 'viewGetEntries',
			'params' => array($where, $hideRejectClosed)
		),
		'get_count' => array(
			'function' => 'viewGetEntriesCount',
			'params' => array($hideRejectClosed, $where)
		),
		'columns' => array(
			'id' => array(
				'header' => array(
					'value' => 'ID',
				),
				'data' => array(
					'db' => 'id',
					'class' => 'centertext',
					'style' => 'width: 10px', // No more!
				),
				'sort' => array(
					'default' => 'id ASC',
					'reverse' => 'id DESC'
				)
			),
			'typeimg' => array(
				'header' => array(
					'value' => '',
				),
				'data' => array(
					'db' => 'typeimg',
					'class' => 'centertext',
					'style' => 'width: 2%',
				),
			),
			'statusimg' => array(
				'header' => array(
					'value' => ''
				),
				'data' => array(
					'db' => 'statusimg',
					'class' => 'centertext',
					'style' => 'width:2%', // Else the attention icon won't look good
				),
			),
			'name' => array(
				'header' => array(
					'value' => $txt['name']
				),
				'data' => array(
					'db' => 'name',
					'class' => 'topic_table subject',
				),
				'sort' => array(
					'default' => 'name ASC',
					'reverse' => 'name DESC'
				)
			),
                        'statusurl' => array(
                                'header' => array(
                                        'value' => $txt['status'],
                                ),
                                'data' => array(
                                        'db' => 'statusurl',
					'class' => 'centertext',
                                ),
				'sort' => array(
					'default' => 'status ASC',
					'reverse' => 'status DESC'
				)
                        ),
			'typeurl' => array(
				'header' => array(
					'value' => $txt['type']
				),
				'data' => array(
					'db' => 'typeurl',
					'class' => 'centertext',
				),
				'sort' => array(
					'default' => 'type ASC',
					'reverse' => 'type DESC'
				)
			),
			'projecturl' => array(
				'header' => array(
					'value' => $txt['project']
				),
				'data' => array(
					'db' => 'projecturl',
					'class' => 'centertext',
				),
				'sort' => array(
					'default' => 'project ASC',
					'reverse' => 'project DESC'
				)
                        )
		)
	);

	return $listOptions;
}

function createListOptionsImportant($basehref, $where = '')
{
	global $context, $txt, $modSettings;
			
	$listOptions = array(
		'id' => 'fxt_important',
		'title' => sprintf($txt['items_attention'], viewGetImportantCount($where)),
		'items_per_page' => $modSettings['defaultMaxMessages'],
		'no_items_label' => $txt['no_items_attention'],
		'base_href' => $basehref,
		'default_sort_col' => 'id',
		'default_sort_dir' => 'desc',
		'start_var_name' => 'impstart',
		'request_vars' => array(
			'desc' => 'impdesc',
			'sort' => 'impsort',
		),
		'get_items' => array(
			'function' => 'viewGetImportant',
			'params' => array($where)
		),
		'get_count' => array(
			'function' => 'viewGetImportantCount',
			'params' => array($where)
		),
		'columns' => array(
			'id' => array(
				'header' => array(
					'value' => 'ID',
				),
				'data' => array(
					'db' => 'id',
					'class' => 'centertext',
					'style' => 'width: 10px', // No more!
				),
				'sort' => array(
					'default' => 'id ASC',
					'reverse' => 'id DESC'
				)
			),
			'typeimg' => array(
				'header' => array(
					'value' => '',
				),
				'data' => array(
					'db' => 'typeimg',
					'class' => 'centertext',
					'style' => 'width: 2%',
				),
			),
			'statusimg' => array(
				'header' => array(
					'value' => ''
				),
				'data' => array(
					'db' => 'statusimg',
					'class' => 'centertext',
					'style' => 'width:2%', // Else the attention icon won't look good
				),
			),
			'name' => array(
				'header' => array(
					'value' => $txt['name']
				),
				'data' => array(
					'db' => 'name',
					'class' => 'topic_table subject',
				),
				'sort' => array(
					'default' => 'name ASC',
					'reverse' => 'name DESC'
				)
			),
                        'statusurl' => array(
                                'header' => array(
                                        'value' => $txt['status'],
                                ),
                                'data' => array(
                                        'db' => 'statusurl',
					'class' => 'centertext',
                                ),
				'sort' => array(
					'default' => 'status ASC',
					'reverse' => 'status DESC'
				)
                        ),
			'typeurl' => array(
				'header' => array(
					'value' => $txt['type']
				),
				'data' => array(
					'db' => 'typeurl',
					'class' => 'centertext',
				),
				'sort' => array(
					'default' => 'type ASC',
					'reverse' => 'type DESC'
				)
			),
			'projecturl' => array(
				'header' => array(
					'value' => $txt['project']
				),
				'data' => array(
					'db' => 'projecturl',
					'class' => 'centertext',
				),
				'sort' => array(
					'default' => 'project ASC',
					'reverse' => 'project DESC'
				)
                        )
		)
	);

	return $listOptions;
	
}

?>