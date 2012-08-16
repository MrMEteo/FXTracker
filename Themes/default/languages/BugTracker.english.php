<?php

/* FXTracker Language File - English */
$txt['bugtracker'] = 'Bug Tracker';
$txt['bugtracker_index'] = 'Tracker Index';
$txt['bugtracker_latest'] = 'Latest Entries';
$txt['bugtracker_latest_issues'] = 'Latest Issues';
$txt['bugtracker_latest_features'] = 'Latest Features';
$txt['bugtracker_no_latest_entries'] = 'There are no latest entries.';
$txt['no_items_attention'] = 'There are no items requiring attention.';
$txt['no_items'] = 'There are no entries.';
$txt['no_projects'] = 'There are no projects.';
$txt['bugtracker_projects'] = 'Projects';
$txt['issues'] = '%s issues';
$txt['features'] = '%s features';
$txt['entry_no_exist'] = 'The requested entry does not exist (anymore).';
$txt['project_no_exist'] = 'The requested project does not exist (anymore).';
$txt['entry_is_private'] = 'The requested entry is marked as private, and thus you can\'t see it.';
$txt['entry_unable_mark'] = 'You cannot mark this entry.';
$txt['entry_mark_failed'] = 'Failed to mark entry.';
$txt['edit_entry_noaccess'] = 'You do not have permission to edit entries.';
$txt['edit_entry_else_noaccess'] = 'You do not have permission to edit someone else\'s entry.';
$txt['add_entry_noaccess'] = 'You do not have permission to add new entries.';
$txt['remove_entry_noaccess'] = 'You do not have permission to remove entries.';
$txt['view_title'] = '#%s - Bug Tracker';
$txt['entrytitle'] = 'Entry no. #%d - %s';
$txt['description'] = 'Description';
$txt['clicktoshow'] = 'Click to show';
$txt['type'] = 'Type';
$txt['bugtracker_feature'] = 'Feature';
$txt['bugtracker_issue'] = 'Issue';
$txt['tracker'] = 'Tracker';
$txt['created_by'] = 'started by %s';
$txt['private_issue'] = 'private issue';
$txt['really_delete'] = 'Really delete this entry? This cannot be undone!';
$txt['view_all_lc'] = 'view all';
$txt['view_all'] = 'View all of kind "%s"';
$txt['tracked_by_guest'] = 'Tracked by Guest on %s';
$txt['tracked_by_user'] = 'Tracked by <a href="%2$s">%3$s</a> on %1$s';

$txt['bt_acp_button'] = 'FXTracker';
$txt['bt_acp_projects'] = 'Manage Projects';
$txt['bt_acp_trash'] = 'View Trash';

// Project Manager
$txt['project_id'] = '#';
$txt['project_name'] = 'Project Name';
$txt['project_issues'] = 'Issues';
$txt['project_features'] = 'Features';
$txt['project_desc'] = 'Project Description';
$txt['project_delete'] = 'Delete';
$txt['project_really_delete'] = 'Really delete this project, including all it\'s entries and notes? This cannot be undone, and entries won\'t be moved to the trash can!';
// End Project Manager

$txt['status'] = 'Status';
$txt['mark_new'] = 'Mark as unassigned';
$txt['mark_wip'] = 'Mark as Work In Progress';
$txt['mark_done'] = 'Mark as resolved';
$txt['mark_reject'] = 'Mark as rejected';
$txt['mark_attention'] = 'Requires Attention';
$txt['mark_attention_undo'] = 'Does Not Require Attention';
$txt['status_new'] = 'Unassigned';
$txt['status_wip'] = 'Work In Progress';
$txt['status_done'] = 'Resolved';
$txt['status_reject'] = 'Rejected';
$txt['status_attention'] = 'Requires Attention';
$txt['shortdesc'] = 'Short Description';

$txt['items_attention'] = '%d item(s) requiring attention';

$txt['go_notes'] = 'Go to notes';
$txt['editentry'] = 'Edit Entry';
$txt['removeentry'] = 'Remove Entry';

$txt['project'] = 'Project';

$txt['viewclosed'] = 'View resolved entries';
$txt['hideclosed'] = 'Hide resolved entries';
$txt['viewrejected'] = 'View rejected entries';
$txt['hiderejected'] = 'Hide rejected entries';
$txt['restore'] = 'Restore';

$txt['new_entry'] = 'New entry';

$txt['action_log'] = 'Action Log';

$txt['entry_edit'] = 'Edit entry';
$txt['entry_edit_lt'] = 'Editing entry "%s"';
$txt['entry_add'] = 'Add new entry';
$txt['entry_title'] = 'Entry title';
$txt['entry_progress'] = 'Progress';
$txt['entry_progress_optional'] = 'Progress (optional)';
$txt['entry_type'] = 'Entry type';
$txt['entry_desc'] = 'Entry description';
$txt['entry_private'] = 'This entry is private';
$txt['entry_mark_optional'] = 'Mark this entry (optional)';
$txt['entry_submit'] = 'Submit';
$txt['save_failed'] = 'Failed to save the given data.';
$txt['entry_posted_in'] = 'This entry will be posted in <strong>%s</strong>';
$txt['no_title'] = 'You didn\'t specify an entry title!';
$txt['no_description'] = 'You didn\'t enter a description!';
$txt['no_type'] = 'You didn\'t select a type!';
$txt['entry_added'] = 'The entry has been successfully added!';

$txt['progress'] = 'Progress';
$txt['na'] = 'N/A';

$txt['no_such_project'] = 'There is no such project';

$txt['info_centre'] = 'Info Center';
$txt['total_entries'] = 'Total entries:';
$txt['total_projects'] = 'Total projects:';
$txt['total_issues'] = 'Total issues:';
$txt['total_features'] = 'Total features:';
$txt['total_attention'] = 'Total requiring attention:';

$txt['notes'] = 'Notes';

// 1: user name, 2: date, 3: url to user profile
$txt['note_by'] = 'Note left by <a href="%3$s"><strong>%1$s</strong></a> on %2$s:';
$txt['note_by_guest'] = 'Note left by <strong>Guest</strong> on %s';
$txt['note_delete_failed'] = 'An error occured while removing the note.';
$txt['note_delete_cannot'] = 'You are not permitted to remove this note.';
$txt['note_delete_notyours'] = 'You cannot remove someone else\'s notes.';
$txt['really_delete_note'] = 'Really delete this note? This cannot be undone!';
$txt['remove_note'] = 'Remove note';
$txt['note_no_exist'] = 'This note doesn\'t exist (anymore).';
$txt['note_save_failed'] = 'An error occured while saving the note. Please try submitting it again.';
$txt['note_edit_notyours'] = 'You cannot edit someone else\'s notes.';
$txt['edit_note'] = 'Edit note';
$txt['add_note'] = 'Add note';
$txt['no_notes'] = 'There are no notes to display.';
$txt['cannot_add_note'] = 'You cannot add a note to this entry.';
$txt['note_empty'] = 'You didn\'t enter a note!';
$txt['quick_note'] = 'Quick Note';

$txt['note_pm_subject'] = 'A note has been added to your entry!';
$txt['note_pm_message'] = 'This is a notification to let you know that user [url=%1$s][b]%2$s[/b][/url] has posted a new note to your entry.
You can [url=%3$s]check your entry out[/url] in the Bug Tracker!

Link to Note:
%4$s

[b]This message has been automatically generated by the bug tracker.[/b]';
$txt['note_pm_message_guest'] = 'This is a notification to let you know that a guest has posted a new note to your entry.
You can [url=%1$s]check your entry out[/url] in the Bug Tracker!

Link to Note:
%2$s

[b]This message has been automatically generated by the bug tracker.[/b]';
$txt['note_pm_username'] = 'Bug Tracker';

// Permissions
$txt['permissiongroup_fxt_classic'] = 'FXTracker Permissions';
$txt['permissiongroup_simple_fxt_simple'] = 'FXTracker Permissions';
$txt['permissionname_bugtracker_view'] = 'View the bug tracker';
$txt['permissionname_bugtracker_viewprivate'] = 'View private entries';
$txt['permissionname_bt_add'] = 'Add new entries';
$txt['permissionname_bt_edit_any'] = 'Edit any entry';
$txt['permissionname_bt_edit_own'] = 'Edit own entry';
$txt['permissionname_bt_remove_any'] = 'Remove any entry';
$txt['permissionname_bt_remove_own'] = 'Remove own entry';
$txt['permissionname_bt_mark_any'] = 'Mark any entry (master permission for marking)';
$txt['permissionname_bt_mark_own'] = 'Mark own entry (master permission for marking)';
$txt['permissionname_bt_mark_new_any'] = 'Mark any entry as new';
$txt['permissionname_bt_mark_new_own'] = 'Mark own entry as new';
$txt['permissionname_bt_mark_wip_any'] = 'Mark any entry as Work In Progress';
$txt['permissionname_bt_mark_wip_own'] = 'Mark own entry as Work In Progress';
$txt['permissionname_bt_mark_done_any'] = 'Mark any entry as resolved';
$txt['permissionname_bt_mark_done_own'] = 'Mark own entry as resolved';
$txt['permissionname_bt_mark_reject_any'] = 'Mark any entry as rejected';
$txt['permissionname_bt_mark_reject_own'] = 'Mark own entry as rejected';
$txt['permissionname_bt_mark_attention_any'] = 'Mark any entry as requiring attention';
$txt['permissionname_bt_mark_attention_own'] = 'Mark own entry as requiring attention';
$txt['permissionname_bt_add_note_any'] = 'Add a note to any entry';
$txt['permissionname_bt_add_note_own'] = 'Add a note to own entry';
$txt['permissionname_bt_edit_note_any'] = 'Edit any note';
$txt['permissionname_bt_edit_note_own'] = 'Edit own note';
$txt['permissionname_bt_remove_note_any'] = 'Remove any note';
$txt['permissionname_bt_remove_note_own'] = 'Remove own note';

$txt['cannot_bugtracker_view'] = 'Sorry, but you do not have permission to view the bug tracker.';
$txt['cannot_bt_add'] = 'Sorry, you are not allowed to add new entries to the bug tracker.';
$txt['cannot_bt_add_note'] = 'Sorry, you are not allowed to add notes to entries in the bug tracker.';

?>
