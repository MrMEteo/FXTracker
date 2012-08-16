<?php

function ManageProjectsMain()
{
        global $context;
        
        // Need this.
        loadTemplate('fxt/BTACP', 'btacp');
        
        // Okay... Switch time!
        $areas = array(
                'add' => 'ManageProjectsAdd',
                'home' => 'ManageProjectsIndex',
                'edit' => 'ManageProjectsEdit',
                'remove' => 'ManageProjectsRemove',
        );
        
        $action = 'home';
        
        if (!empty($_GET['sa']) && isset($areas[$_GET['sa']]) && function_exists($areas[$_GET['sa']]))
                $action = $_GET['sa'];
                
        $areas[$action]();
}

function ManageProjectsIndex()
{
        global $context, $smcFunc, $txt, $sourcedir, $modSettings, $scripturl;
        
        // We're going to create a list for this.
        require_once($sourcedir . '/Subs-List.php');
	$listOptions = array(
		'id' => 'fxt_projects',
		'items_per_page' => $modSettings['defaultMaxMessages'],
		'no_items_label' => $txt['project_no_exist'],
		'base_href' => $scripturl . '?action=admin;area=projects',
		'default_sort_col' => 'id',
		'get_items' => array(
			'function' => 'grabListProjects',
			'params' => array()
		),
		'get_count' => array(
			'function' => 'grabListProjectCount',
			'params' => array()
		),
		'columns' => array(
			'id' => array(
				'header' => array(
					'value' => '#'
				),
				'data' => array(
					'db' => 'id',
                                        'style' => 'width: 10px;',
				),
				'sort' => array(
					'default' => 'id ASC',
					'reverse' => 'id DESC'
				)
			),
			'name' => array(
				'header' => array(
					'value' => $txt['project_name']
				),
				'data' => array(
					'db' => 'name',
                                        'style' => 'width: 20%',
				),
				'sort' => array(
					'default' => 'name ASC',
					'reverse' => 'name DESC'
				)
			),
                        'description' => array(
                                'header' => array(
                                        'value' => $txt['project_desc'],
                                ),
                                'data' => array(
                                        'db' => 'description',
                                ),
                                'sort' => array(
                                        'default' => 'description ASC',
                                        'reverse' => 'description DESC',
                                )
                        ),
			'issuenum' => array(
				'header' => array(
					'value' => $txt['project_issues']
				),
				'data' => array(
					'db' => 'featurenum',
                                        'class' => 'centertext',
					'style' => 'width: 40px',
				),
				'sort' => array(
					'default' => 'issuenum ASC',
					'reverse' => 'issuenum DESC'
				)
			),
			'featurenum' => array(
				'header' => array(
					'value' => $txt['project_features']
				),
				'data' => array(
					'db' => 'featurenum',
					'class' => 'centertext',
					'style' => 'width: 40px',
				),
				'sort' => array(
					'default' => 'featurenum ASC',
					'reverse' => 'featurenum DESC'
				)
			),
                        'delete' => array(
                                'header' => array(
                                        'value' => $txt['project_delete'],
                                ),
                                'data' => array(
                                        'db' => 'deleteurl',
                                        'class' => 'righttext',
                                        'style' => 'width:20px',
                                )
                        )
		)
	);

	// Create the list
	createList($listOptions);
        
        // Hand this over to the templates. We're done here!
        $context['sub_template'] = 'BTACPManageProjectsIndex';
}

function grabListProjects($start, $items_per_page, $sort)
{
        global $context, $smcFunc, $scripturl, $txt, $settings;
        
	// Query time folks!
	$result = $smcFunc['db_query']('', '
		SELECT
                        id, name, description, issuenum, featurenum
		FROM {db_prefix}bugtracker_projects
		ORDER BY ' . $sort . '
		LIMIT ' . $start . ', ' . $items_per_page,
		array()
	);
        
        // Format them.
        $projects = array();
        while ($project = $smcFunc['db_fetch_assoc']($result))
        {
                $projects[$project['id']] = array(
                        'id' => $project['id'],
                        'name' => '<a href="' . $scripturl . '?action=admin;area=projects;sa=edit;project=' . $project['id'] . '">' . $project['name'] . '</a>', // This is filtered on save.
                        'description' => $project['description'], // Also filtered
                        'issuenum' => $project['issuenum'],
                        'featurenum' => $project['featurenum'],
                        'deleteurl' => '
                        <a href="' . $scripturl . '?action=admin;area=projects;sa=remove;project=' . $project['id'] . '" onclick="return confirm(' . javascriptescape($txt['project_really_delete']) . ')">
                                <img src="' . $settings['images_url'] . '/bugtracker/reject.png" alt="" />
                        </a>'
                );
        }
        
        // You're free, $result!
        $smcFunc['db_free_result']($result);
        
        return $projects;
}

function grabListProjectCount()
{
        // Need this for queries.
	global $smcFunc;

	// As we requested that we might also need it...
	$request = $smcFunc['db_query']('', '
		SELECT COUNT(id) AS project_count
		FROM {db_prefix}bugtracker_projects',
		array()
	);

	// Countin' our way up.
	list ($count) = $smcFunc['db_fetch_row']($request);

	// And give us some free space.
	$smcFunc['db_free_result']($request);

	// This is how many we have.
	return $count;
}

function ManageProjectsEdit()
{
        global $context, $smcFunc;
        
        // Is the project numeric and does it exist...?
        if (!isset($_GET['project']) || !is_numeric($_GET['project']) || !isset($context['bugtracker']['projects'][$_GET['project']]))
                fatal_lang_error('project_no_exist');
}