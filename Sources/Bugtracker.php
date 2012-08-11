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
	// 'action' => array('source file', 'bug tracker function'),
	$sactions = array(
		'addnote' => array('Add', 'AddNote'),
		'addnote2' => array('Add', 'AddNote2'),
		'admin' => array('Admin', 'Admin'),
		
		'credits' => array('Credits', 'Credits'),

		'edit' => array('Edit', 'Edit'),
		'edit2' => array('Edit', 'SubmitEdit'),
		'editnote' => array('Edit', 'EditNote'),
		'editnote2' => array('Edit', 'EditNote2'),

		'home' => array('Home', 'Home'),

		'mark' => array('Edit', 'MarkEntry'),

		'new' => array('Add', 'NewEntry'),
		'new2' => array('Add', 'SubmitNewEntry'),

		'projectindex' => array('View', 'ViewProject'),

		'remove' => array('Remove', 'RemoveEntry'),
		
		'removenote' => array('Remove', 'RemoveNote'),

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
	
	// Action is home? We forgot to include something, then!
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
