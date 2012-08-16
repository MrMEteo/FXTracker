<?php

/* FXTracker Project Template */

function template_TrackerViewProject()
{
	global $context, $scripturl, $txt, $settings;

	echo '
	<div class="buttonlist">
		<ul>
			<li>
				<a', $context['bugtracker']['view']['closed'] ? ' class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=projectindex;project=', $context['bugtracker']['project']['id'] . $context['bugtracker']['view']['link']['closed'], '">
					<span>', $context['bugtracker']['view']['closed'] ? $txt['hideclosed'] : $txt['viewclosed'] . ' [' . $context['bugtracker']['num_closed'] . ']', '</span>
				</a>
			</li>
			<li>
				<a', $context['bugtracker']['view']['rejected'] ? ' class="active"' : '', ' href="', $scripturl, '?action=bugtracker;sa=projectindex;project=', $context['bugtracker']['project']['id'] . $context['bugtracker']['view']['link']['rejected'], '">
					<span>', $context['bugtracker']['view']['rejected'] ? $txt['hiderejected'] : $txt['viewrejected'] . ' [' . $context['bugtracker']['num_rejected'] . ']', '</span>
				</a>
			</li>';
			
	// A restore button?
	if ($context['bugtracker']['view']['rejected'] || $context['bugtracker']['view']['closed'])
		echo '
			<li>
				<a href="', $scripturl, '?action=bugtracker;sa=projectindex;project=', $context['bugtracker']['project']['id'], '">
					<span>', $txt['restore'], '</span>
				</a>
			</li>';

	// Are we allowed to add a new entry?
	if ($context['can_bt_add'])
		echo '
			<li>
				<a class="active" href="', $scripturl, '?action=bugtracker;sa=new;project=', $context['bugtracker']['project']['id'], '">
					<span>', $txt['new_entry'], '</span>
				</a>
			</li>';

			
	// Just headers.
	echo '
		</ul>
	</div><br />';
	
	// And our list!
	template_show_list('fxt_view');
	
	echo '
	<br />';

	template_show_list('fxt_important');

	echo '
	<br class="clear" />';
}

?>
