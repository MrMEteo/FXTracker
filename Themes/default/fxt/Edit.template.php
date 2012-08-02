<?php

/* FXTracker Edit Template */

function template_BugTrackerEdit()
{
	// Globalling.
	global $context, $scripturl, $txt;

	// Types a bit quicker.
	$entry = $context['bugtracker']['entry'];

	// Start our form.
	echo '
	<form action="', $scripturl, '?action=bugtracker;sa=edit2" method="post">';

	// Then, for the general information.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['entry_edit'], '
		</h3>
	</div>
	<div class="windowbg">
		<span class="topslice"><span></span></span>
		<div class="fullpadding">
			<table class="fullwidth">';

	// The entry title. Lets start with that.
	echo '
				<tr>
					<td class="halfwidth">
						<strong>', $txt['title'], '</strong>
					</td>
					<td class="halfwidth">
						<input type="text" name="entry_title" class="fullwidth" value="', $entry['name'], '" />
					</td>
				</tr>';

	// To what should we mark this?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<select name="entry_mark">
							<option value="new"', ($entry['status'] == 'new' ? ' selected="selected"' : ''), '>', $txt['mark_new'], '</option>
							<option value="wip"', ($entry['status'] == 'wip' ? ' selected="selected"' : ''), '>', $txt['mark_wip'], '</option>
							<option value="done"', ($entry['status'] == 'done' ? ' selected="selected"' : ''), '>', $txt['mark_done'], '</option>
							<option value="reject"', ($entry['status'] == 'reject' ? ' selected="selected"' : ''), '>', $txt['mark_reject'], '</option>
						</select>
					</td>
				</tr>';

	// What kind of thing is this? Set the type, please.
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<select name="entry_type">
							<option value="" disabled="disabled">', $txt['type'], '</option>
							<option value="issue"', ($entry['type'] == 'issue' ? ' selected="selected"' : ''), '>', $txt['bugtracker_issue'], '</option>
							<option value="feature"', ($entry['type'] == 'feature' ? ' selected="selected"' : ''), '>', $txt['bugtracker_feature'], '</option>
						</select>
					</td>
				</tr>';
	
	// Does this entry need to be private?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<input type="checkbox" name="entry_private" value="true"', ($entry['private'] ? ' checked="checked"' : ''), ' /> ', $txt['entry_private'], '
					</td>
				</tr>';

	// Or does it need attention?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<input type="checkbox" name="entry_attention" value="true"', ($entry['attention'] ? ' checked="checked"' : ''), ' /> ', $txt['mark_attention'], '
					</td>
				</tr>';
				
	// Gotta change the progress?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<select name="entry_progress">
							<option value="0" disabled="disabled">', $txt['entry_progress'], '</option>';
							
	// Do it this way, since that is *quite* a bit quicker.
	$progvalues = array(0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100);
	foreach ($progvalues as $prog)
	{
		echo '
							<option value="', $prog, '"', $entry['progress'] == $prog ? ' selected="selected"' : '', '>', $prog, '%</option>';
	}
	
	echo '
						</select>
					</td>
				</tr>';

	// Close everything. And start the editor.
	echo '
			</table>

			<hr />
			
			<div id="bbcBox_message"></div>
			<div id="smileyBox_message"></div>
			', template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message') . '<br /><hr />';

	// Some hidden stuff.
	echo '
			<input type="hidden" name="entry_id" value="', $entry['id'], '" />
			<input type="hidden" name="is_fxt" value="true" />';

	// And our submit button and closing stuff.
	echo '	
			<div class="floatright">
				<input type="submit" value="', $txt['entry_submit'], '" class="button_submit" />
			</div>
			<br class="clear" />
		</div>		
		<span class="botslice"><span></span></span>
	</div>';

	// Close the form.
	echo '
	</form>';

	// Because content will break otherwise.
	echo '
	<br class="clear" />';
}

?>