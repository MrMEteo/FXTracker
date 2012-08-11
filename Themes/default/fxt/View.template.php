<?php

/* FXTracker View Template */

function template_TrackerView()
{
	global $context, $txt, $scripturl, $settings;

	// Is this new?
	if ($context['bugtracker']['entry']['is_new'])
		echo '
	<div class="information"><strong>', $txt['entry_added'], '</strong></div>';

	// Show some information about this entry.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			<img class="icon" src="', $settings['images_url'], '/bugtracker/', $context['bugtracker']['entry']['type'], '.png" alt="" />
			', sprintf($txt['entrytitle'], $context['bugtracker']['entry']['id'], $context['bugtracker']['entry']['name']), '
		</h3>
	</div>';
	
	// Allow people to jump to the notes?
	echo '
	<div class="buttonlist floatleft">
		<ul>
			<li>
				<a href="#notes">
					<span>', $txt['go_notes'], '</span>
				</a>
			</li>
		</ul>
	</div>';
	
	echo '
	<div class="buttonlist floatright">
		<ul>';

	// Are we allowed to reply to this entry? Remove the "false" check on here to make the button appear like normal, done like this because comments haven't been implemented yet.
	if (false || $context['can_bt_reply_any'] || $context['can_bt_reply_own'])
		echo '
			<li>
				<a class="active" href="', $scripturl, '?action=bugtracker;sa=reply;entry=', $context['bugtracker']['entry']['id'], '"><span>', $txt['reply'], '</span></a>
			</li>';
	
	// Are we allowed to edit this entry?
	if ($context['can_bt_edit_any'] || $context['can_bt_edit_own'])
		echo '
			<li>
				<a href="', $scripturl, '?action=bugtracker;sa=edit;entry=', $context['bugtracker']['entry']['id'], '"><span>', $txt['editentry'], '</span></a>
			</li>';

	// Or allowed to remove it?
	if ($context['can_bt_remove_any'] || $context['can_bt_remove_own'])
		echo '
			<li>
				<a onclick="return confirm(', javascriptescape($txt['really_delete']), ')" href="', $scripturl, '?action=bugtracker;sa=remove;entry=', $context['bugtracker']['entry']['id'], '"><span>', $txt['removeentry'], '</span></a>
			</li>';

	// Show the actual entry.
	echo '
		</ul>
	</div>
	<table class="fullwidth">
		<tr>
			<td style="width: 5%">';
			
	// This is whatever data we have to show.
	echo '
				<div class="plainbox righttext">
					<strong>', $txt['title'], ':</strong><br />
					<strong>', $txt['type'], ':</strong><br />
					<strong>', $txt['tracker'], ':</strong><br />
					<strong>', $txt['status'], ':</strong><br />
					<strong>', $txt['project'], ':</strong><br />
					', $context['bugtracker']['entry']['status'] == 'wip' ? '<strong>' . $txt['progress'] . '</strong><br />' : '', '
				</div>';
				
	// Continue with our table...
	echo '
			</td>
			<td style="width: 95%">';
			
	// And this is the actual data!
	echo '
				<div class="plainbox">
					', $context['bugtracker']['entry']['name'], '<br />

					<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $context['bugtracker']['entry']['type'], '">', $txt['bugtracker_' . $context['bugtracker']['entry']['type']], '</a><br />

					<a href="', $scripturl, '?action=profile;u=', $context['bugtracker']['entry']['tracker']['id_member'], '">', $context['bugtracker']['entry']['tracker']['member_name'], '</a> (', empty($context['bugtracker']['entry']['tracker']['member_group']) ? $context['bugtracker']['entry']['tracker']['post_group'] : $context['bugtracker']['entry']['tracker']['member_group'], ')<br />

					<a href="', $scripturl, '?action=bugtracker;sa=viewstatus;status=', $context['bugtracker']['entry']['status'], '">', $txt['status_' . $context['bugtracker']['entry']['status']] . '</a>' . ($context['bugtracker']['entry']['attention'] ? ' <strong>(' . $txt['status_attention'] . ')</strong>' : ''), '<br />

					<a href="', $scripturl, '?action=bugtracker;sa=projectindex;project=', $context['bugtracker']['entry']['project']['id'], '">', $context['bugtracker']['entry']['project']['name'], '</a><br />

					', $context['bugtracker']['entry']['status'] == 'wip' ? $context['bugtracker']['entry']['progress'] . '<br />' : '', '
				</div>';
				
	// Enough table -- Stop it.
	echo '
			</td>
		</tr>
	</table>';
	
	// Entry description...
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			<img class="icon" src="', $settings['images_url'], '/bugtracker/description.png" alt="" />
			', $txt['description'], '
		</h3>
	</div>
	<div class="windowbg2">		
		<span class="topslice"><span></span></span>
		<div class="fullmargin">
			', $context['bugtracker']['entry']['desc'], '
		</div>
		<span class="botslice"><span></span></span>
	</div>';

	// Allowed to mark?
	if ($context['bt_can_mark'])
	{
		echo '

	<div class="buttonlist floatright">
		<ul>';
		
		// Mark as unassigned/new?
		if ($context['can_bt_mark_new_any'] || $context['can_bt_mark_new_own'])
			echo '
			<li>
				<a ', $context['bugtracker']['entry']['status'] == 'new' ? 'class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=mark;as=new;entry=', $context['bugtracker']['entry']['id'], '">
					<span>', $txt['mark_new'], '</span>
				</a>
			</li>';
			
		// Or as Work In Progress?
		if ($context['can_bt_mark_wip_any'] || $context['can_bt_mark_wip_own'])
			echo '
			<li>
				<a ', $context['bugtracker']['entry']['status'] == 'wip' ? 'class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=mark;as=wip;entry=', $context['bugtracker']['entry']['id'], '">
					<span>', $txt['mark_wip'], '</span>
				</a>
			</li>';
			
		// Mark as Resolved?
		if ($context['can_bt_mark_done_any'] || $context['can_bt_mark_done_own'])
			echo '
			<li>
				<a ', $context['bugtracker']['entry']['status'] == 'done' ? 'class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=mark;as=done;entry=', $context['bugtracker']['entry']['id'], '">
					<span>', $txt['mark_done'], '</span>
				</a>
			</li>';
			
		// Then as Rejected?
		if ($context['can_bt_mark_reject_any'] || $context['can_bt_mark_reject_own'])
			echo '
			<li>
				<a ', $context['bugtracker']['entry']['status'] == 'reject' ? 'class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=mark;as=reject;entry=', $context['bugtracker']['entry']['id'], '">
					<span>', $txt['mark_reject'], '</span>
				</a>
			</li>';
		echo '
		</ul>
	</div>';
	}

	// If we want it to be urgent, mark it as requiring attention!
	if ($context['can_bt_mark_attention_any'] || $context['can_bt_mark_attention_own'])
		echo '
	<div class="buttonlist floatleft">
		<ul>
			<li>
				<a ', $context['bugtracker']['entry']['attention'] ? 'class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=mark;as=attention;entry=', $context['bugtracker']['entry']['id'], '">
					<span>', $context['bugtracker']['entry']['attention'] ? $txt['mark_attention_undo'] : $txt['mark_attention'], '</span>
				</a>
			</li>
		</ul>
	</div>';
	
	// The Notes of this entry...
	echo '
	<br class="clear" /><br />
	
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['notes'], '
		</h3>
	</div>';
	
	// Are we allowed to add notes? :D
	if ($context['can_bt_add_note_any'] || $context['can_bt_add_note_own'])
		echo '
	<a name="notes"></a>
	<div class="buttonlist">
		<ul>
			<li>
				<a class="active" href="', $scripturl, '?action=bugtracker;sa=addnote;entry=', $context['bugtracker']['entry']['id'], '">
					<span>', $txt['add_note'], '</span>
				</a>
			</li>
		</ul>
	</div><br class="clear" />';
		
	// Do we have any notes?
	if (!empty($context['bugtracker']['entry']['notes']))
	{
		foreach ($context['bugtracker']['entry']['notes'] as $note)
		{
			// Start the note.
			echo '
	<div class="plainbox">';
	
			// Build the array of buttons.
			$buttons = array();
			
			// Edit it?
			if (allowedTo('bt_edit_note_any') || (allowedTo('bt_edit_note_own') && $context['user']['id'] == $note['user']['id_member']))
				$buttons[] = '<a href="' . $scripturl . '?action=bugtracker;sa=editnote;note=' . $note['id'] . '">' . $txt['edit_note'] . '</a>';
		
			// Can we remove this note?
			if (allowedTo('bt_remove_note_any') || (allowedTo('bt_remove_note_own') && $context['user']['id'] == $note['user']['id_member']))
				$buttons[] = '<a onclick="return confirm(' . javascriptescape($txt['really_delete_note']) . ')" href="' . $scripturl . '?action=bugtracker;sa=removenote;note=' . $note['id'] . '">' . $txt['remove_note'] . '</a>';
			
			// If we have buttons, show them.
			if (!empty($buttons))
				echo '<div class="floatright">' . implode(' | ', $buttons) . '</div>';
			
			// Then show the note itself.
			echo '
		<a name="note_', $note['id'], '"></a>
		', sprintf($txt['note_by'], $note['user']['member_name'], $note['time'], $scripturl . '?action=profile;u=' . $note['user']['id_member']), '</a>
		<hr />
		', $note['text'], '
	</div>';
		
		}
	}
	// Aww, we have no notes?
	else
		echo '
	<div class="plainbox centertext">
		<strong>', $txt['no_notes'], '</strong>
	</div>';
	
	echo '
	<br class="clear" />';
}

?>