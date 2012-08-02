<?php

/* FXTracker Add Template */

function template_BugTrackerAddNew()
{
	// Globalling.
	global $context, $scripturl, $txt;

	// Start our form.
	echo '
	<form action="', $scripturl, '?action=bugtracker;sa=new2" method="post">';

	// Then, for the general information.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['entry_add'], '
		</h3>
	</div>
	<div class="windowbg">
		<span class="topslice"><span></span></span>
		<div style="margin-left:10px">
			<table class="fullwidth">';

	// The entry title. Lets start with that.
	echo '
				<tr>
					<td class="halfwidth">
						<strong>', $txt['title'], '</strong>
					</td>
					<td class="halfwidth">
						<input type="text" style="width: 98%" name="entry_title" value="" />
					</td>
				</tr>';

	// A lil' space. Then some text.

	// To what should we mark this?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<select name="entry_mark">
							<option value="new" selected="selected">', $txt['entry_mark_optional'], '</option>
							<option value="new">', $txt['mark_new'], '</option>
							<option value="wip">', $txt['mark_wip'], '</option>
							<option value="done">', $txt['mark_done'], '</option>
							<option value="reject">', $txt['mark_reject'], '</option>
						</select>
					</td>
				</tr>';

	// What kind of thing is this? Set the type, please.
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<select name="entry_type">
							<option value="" disabled="disabled" selected="selected">', $txt['type'], '</option>
							<option value="issue">', $txt['bugtracker_issue'], '</option>
							<option value="feature">', $txt['bugtracker_feature'], '</option>
						</select>
					</td>
				</tr>';
	
	// Does this entry need to be private?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<input type="checkbox" name="entry_private" value="true" /> ', $txt['entry_private'], '
					</td>
				</tr>';

	// Or does it need attention?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<input type="checkbox" name="entry_attention" value="true" /> ', $txt['mark_attention'], '
					</td>
				</tr>';
				
	// Gotta change the progress?
	echo '
				<tr>
					<td class="halfwidth"></td>
					<td class="halfwidth">
						<select name="entry_progress">
							<option value="0" selected="selected">', $txt['entry_progress_optional'], '</option>';
							
	// Do it this way, since that is *quite* a bit quicker.
	$progvalues = array(0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100);
	foreach ($progvalues as $prog)
	{
		echo '
							<option value="', $prog, '">', $prog, '%</option>';
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

	// Some users need extra choice.
	echo sprintf($txt['entry_posted_in'], $context['bugtracker']['project']['name']);

	// Some hidden stuff.
	echo '
			<input type="hidden" name="entry_projectid" value="', $context['bugtracker']['project']['id'], '" />
			<input type="hidden" name="is_fxt" value="true" />';

	// And our submit button and closing stuff.
	echo '	
			<div class="floatright" style="margin-right:10px">
				<input type="submit" value="', $txt['entry_submit'], '" class="button_submit" />
			</div>
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