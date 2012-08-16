<?php

/* FXTracker - Hooks */

function fxt_actions(&$actionArray)
{
	// Add the action! Quick!
	$actionArray['bugtracker'] = array('Bugtracker.php', 'BugTrackerMain');
}

function fxt_permissions(&$permissionGroups, &$permissionList)
{
	// Load the language for this.
	loadLanguage('BugTracker');

	// Permission groups...
	$permissionGroups['membergroup']['simple'] = array('fxt_simple');
	$permissionGroups['membergroup']['classic'] = array('fxt_classic');

	// And then the permissions themselves, in all their glory!
	$permissionList['membergroup']['bugtracker_view'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bugtracker_viewprivate'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_add'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_edit_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_edit_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_remove_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_remove_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_new_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_new_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_wip_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_wip_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_done_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_done_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_reject_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_reject_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_attention_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_mark_attention_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_add_note_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_add_note_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_edit_note_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_edit_note_own'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_remove_note_any'] = array(false, 'fxt_classic', 'fxt_simple');
	$permissionList['membergroup']['bt_remove_note_own'] = array(false, 'fxt_classic', 'fxt_simple');
}

function fxt_menubutton(&$menu_buttons)
{
	global $txt, $scripturl;
        loadLanguage('BugTracker');
	$menu_buttons['bugtracker'] = array(
	       'title' => $txt['bugtracker'],
	       'href' => $scripturl . '?action=bugtracker',
		'show' => allowedTo('bugtracker_view'),
		'sub_buttons' => array(
		),
	);
}

function fxt_adminareas(&$areas)
{
	/*
	 *		'maintenance' => array(
			'title' => $txt['admin_maintenance'],
			'permission' => array('admin_forum'),
			'areas' => array(
				'maintain' => array(
					'label' => $txt['maintain_title'],
					'file' => 'ManageMaintenance.php',
					'icon' => 'maintain.gif',
					'function' => 'ManageMaintenance',
					'subsections' => array(
						'routine' => array($txt['maintain_sub_routine'], 'admin_forum'),
						'database' => array($txt['maintain_sub_database'], 'admin_forum'),
						'members' => array($txt['maintain_sub_members'], 'admin_forum'),
						'topics' => array($txt['maintain_sub_topics'], 'admin_forum'),
					),
				),*/
	
	global $txt;
	loadLanguage('BugTracker');
	
	$areas['fxtracker'] = array(
		'title' => $txt['bt_acp_button'],
		'permission' => array('bt_admin'),
		'areas' => array(
			'projects' => array(
				'label' => $txt['bt_acp_projects'],
				'file' => 'FXTracker/ACP/ManageProjects.php',
				'icon' => 'boards.gif',
				'function' => 'ManageProjectsMain',
			)
		)
	);
}