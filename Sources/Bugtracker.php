<?php

/* FXTracker Main File
 * Initializes FXTracker and the main functions.
 */

function BugTrackerMain()
{
	// Our usual stuff.
	global $context, $txt, $sourcedir, $scripturl;

	// Load the language and template. Oh, don't forget our CSS file, either.
	loadLanguage('BugTracker');
	loadTemplate(false, 'bugtracker');

	// Are we allowed to view this?
	isAllowedTo('bugtracker_view');

	// A list of all actions we can take.
	// 'action' => 'bug tracker function',
	$sactions = array(
		'admin' => array('Admin', 'Admin'),
		
		'credits' => array('Credits', 'Credits'),

		'edit' => array('Edit', 'Edit'),
		'edit2' => array('Edit', 'SubmitEdit'),

		'home' => array('Home', 'Home'),

		'mark' => array('Mark', 'MarkEntry'),

		'new' => array('Add', 'NewEntry'),
		'new2' => array('Add', 'SubmitNewEntry'),

		'projectindex' => array('View', 'ViewProject'),

		'remove' => array('Remove', 'RemoveEntry'),

		'maintenance' => array('Maintenance', 'Maintenance'),
		'maintenance2' => array('Maintenance', 'PerformMaintenance'),

		'view' => array('View', 'View'),
		'viewtype' => array('View', 'ViewType'),
		'viewstatus' => array('View', 'ViewStatus'),
	);

	// Allow mod creators to easily snap in.
	call_integration_hook('integrate_bugtracker_actions', array(&$sactions));

	// Default is home.
	$action = 'home';

	// Try to see if we have any other action to use!
	if (!empty($_GET['sa']) && !empty($sactions[$_GET['sa']]) && file_exists($sourcedir . '/FXTracker/' . $sactions[$_GET['sa']][0] . '.php'))
	{
		include($sourcedir . '/FXTracker/' . $sactions[$_GET['sa']][0] . '.php');
			
		if (function_exists('BugTracker' . $sactions[$_GET['sa']][1]))
			$action = $_GET['sa'];
	}
	
	// Action is home?
	if ($action == 'home')
		include($sourcedir . '/FXTracker/Home.php');
	
	// And add a bit onto the linktree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=bugtracker',
		'name' => $txt['bugtracker'],
	);

	// Then, execute the function!
	call_user_func('BugTracker' . $sactions[$action][1]);
}


?>
