<?php

/* FXTracker Home Template */

function template_TrackerHome()
{
	// Global $context and other stuff.
	global $context, $txt, $scripturl, $settings;

	// Our latest issues and features.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			<img src="', $settings['images_url'], '/bugtracker/latest.png" class="icon" alt="" />', $txt['bugtracker_latest'], '
		</h3>
	</div>';

	// These are the latest headers. Title bars, to be exact.
	echo '
	<div class="floatleft" style="width:49.9%">
		<div class="title_barIC">
			<h4 class="titlebg">
				', $txt['bugtracker_latest_issues'], '
			</h4>
		</div>
	</div>
	<div class="floatright" style="width:49.9%">
		<div class="title_barIC">
			<h4 class="titlebg">
				', $txt['bugtracker_latest_features'], '
			</h4>
		</div>
	</div>
	<br class="clear" />';

	// Now for the Latest boxes
	echo '
	<div class="floatleft" style="width:49.9%">
		<div class="plainbox">';

	// Load the list of entries from the latest issues, and display them in a list.
	if (!empty($context['bugtracker']['latest']['issues']))
	{
		// Instead of doing this ourselves, lets have <ol> do the numbering for us.
		echo '
			<ol style="margin:0;padding:0;padding-left:15px">';

		foreach ($context['bugtracker']['latest']['issues'] as $entry)
		{
			echo '
				<li>';
				
					// Probably this entry has a project associated...
					if (!empty($entry['project']))
						echo '
					[<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">
						' . $entry['project']['name'] . '
					</a>]';
					
					// Though it certainly has a name!
					echo '
					#', $entry['id'], ':
					<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
						', $entry['name'], '
					</a>
				</li>';
		}
		
		echo '
			</ol>';
	}
	else
		echo $txt['bugtracker_no_latest_entries'];
		
	echo '
		</div>
	</div>
	<div class="floatright" style="width: 49.9%">
		<div class="plainbox">';

	// Load the list of entries from the latest features. Make a nice list of 'em!
	if (!empty($context['bugtracker']['latest']['features']))
	{
		// Again have <ol> do the work for us. That'll work better.
		echo '
			<ol style="margin:0;padding:0;padding-left:15px">';

		foreach ($context['bugtracker']['latest']['features'] as $entry)
		{
			echo '
				<li>';
				
					// Probably this entry has a project associated...
					if (!empty($entry['project']))
						echo '
					[<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">
						' . $entry['project']['name'] . '
					</a>]';
					
					// Though it certainly has a name!
					echo '
					#', $entry['id'], ':
					<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
						', $entry['name'], '
					</a>
				</li>';
		}

		echo '
			</ol>';
	}
	else
		echo $txt['bugtracker_no_latest_entries'];

	echo '
		</div>
	</div>
	<br class="clear" />';
	
	// Lets start with the projects shall we?
	echo'
	<div class="cat_bar">
		<h3 class="catbg">
			<img src="', $settings['images_url'], '/bugtracker/projects.png" class="icon" alt="" />', $txt['bugtracker_projects'], '
		</h3>
	</div>';

	// Show the project list.
	echo '
	<div class="windowbg">
		<span class="topslice"><span></span></span>
		<div class="" style="margin-left:10px;margin-right:10px">';
		
	foreach ($context['bugtracker']['projects'] as $id => $project)
	{
			// The project name?
			echo '
			<a href="', $scripturl, '?action=bugtracker;sa=projectindex;project=', $id, '"><span class="projsubject">', $project['name'], '</span></a>';
			
			// How many issues and features does this thing have?
			echo ' - ', sprintf($txt['issues'], $project['num']['issues']), ', ', sprintf($txt['features'], $project['num']['features']), '<br />';
			
			// And the description along with other stuff...
			echo '
			<span class="smalltext">', $project['description'], '</span>';
			
			// A HR, if it isn't the last?
			if (end($context['bugtracker']['projects']) != $project)
				echo '
			<hr />';
	}
	if (empty($context['bugtracker']['projects']))
		echo '
		<div class="centertext">', $txt['no_projects'], '</div>';
	echo '
		</div>
		<span class="botslice"><span></span></span>
	</div><br />';
	
	template_show_list('fxt_important');

	// The info centre? TODO
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			<img src="', $settings['images_url'], '/bugtracker/infocenter.png" alt="" class="icon" /> ', $txt['info_centre'], '
		</h3>
	</div>
	<div class="plainbox">
		<strong>', $txt['total_entries'], '</strong> ', count($context['bugtracker']['entries']), '<br />
		<strong>', $txt['total_projects'], '</strong> ', count($context['bugtracker']['projects']), '<br />
		<strong>', $txt['total_issues'], '</strong> ', count($context['bugtracker']['issue']), ' (<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=issue">', $txt['view_all_lc'], '</a>)<br />
		<strong>', $txt['total_features'], '</strong> ', count($context['bugtracker']['feature']), ' (<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=feature">', $txt['view_all_lc'], '</a>)<br />
		<strong>', $txt['total_attention'], '</strong> ', count($context['bugtracker']['attention']), '
	</div>';
	
	// And our last batch of HTML.
	echo '
	<br class="clear" />';
}

?>