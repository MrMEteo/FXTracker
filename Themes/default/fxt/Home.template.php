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

	// These are the latest xxx headers. Title bars, to be exact.
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

	// Now for the Latest xxx boxes
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
				<li>
					', !empty($entry['project']) ? '[<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">
						' . $entry['project']['name'] . '
					</a>] ' : '', '
					#', $entry['id'], ': <a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
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
				<li>
					', !empty($entry['project']) ? '[<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">
						' . $entry['project']['name'] . '
					</a>] ' : '', '
					#', $entry['id'], ': <a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
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
	<br class="clear" />

	<div class="cat_bar">
		<h3 class="catbg">
			<img src="', $settings['images_url'], '/bugtracker/projects.png" class="icon" alt="" />', $txt['bugtracker_projects'], '
		</h3>
	</div>';

	// Show the project list.
	$windowbg = 0;
	foreach ($context['bugtracker']['projects'] as $id => $project)
	{
		echo '
	<div class="windowbg', $windowbg == 0 ? '' : '2', '">
		<span class="topslice"><span></span></span>
		<div class="info" style="margin-left: 10px">
			<a href="', $scripturl, '?action=bugtracker;sa=projectindex;project=', $id, '"><span class="projsubject">', $project['name'], '</span></a> - ', sprintf($txt['issues'], $project['num']['issues']), ', ', sprintf($txt['features'], $project['num']['features']), '<br />
			<span class="smalltext">', $project['description'], '</span>
		</div>
		<span class="botslice"><span></span></span>
	</div>';
		
		$windowbg = ($windowbg == 0 ? 1 : 0);
	}

	echo '<br />
	<div class="cat_bar">
		<h3 class="catbg">
			<img src="', $settings['images_url'], '/bugtracker/attention.png" class="icon" alt="" />', sprintf($txt['items_attention'], count($context['bugtracker']['attention'])), '
		</h3>
	</div>';

	// Show the items requiring attention.
	if (count($context['bugtracker']['attention']) != 0)
	{
		// Headers. Just headers.
		echo '
	<div class="tborder topic_table">
		<table class="table_grid" cellspacing="0" style="width: 100%">
			<thead>
				<tr class="catbg">
					<th scope="col" class="first_th" width="8%" colspan="2">&nbsp;</th>
					<th scope="col">
						', $txt['subject'], '
					</th>
					<th scope="col" width="18%">
						', $txt['status'], '
					</th>
					<th scope="col" width="18%">
						', $txt['type'], '
					</th>
					<th scope="col" width="16%" class="last_th">
						', $txt['project'], '
					</th>
				</tr>
			</thead>
			<tbody>';
		
		// And the content.
		foreach ($context['bugtracker']['attention'] as $entry)
		{
			// Hi! Welcome at entry statio-- oh, we aren't recording?
			echo '
				<tr>';
				
			// Show a nice image depending on the type.
			echo '
					<td class="icon1 windowbg">
						<img src="', $settings['images_url'], '/bugtracker/', $entry['type'], '.png" alt="" />
					</td>';
					
			// And on the status!
			echo '
					<td class="icon2 windowbg">
						<img src="' . $settings['images_url'] . '/bugtracker/', $entry['status'] == 'wip' ? 'wip.gif' : $entry['status'] . '.png', '" alt="" />
					</td>';
					
			// Then on to the entry title and short description.
			echo '
					<td class="subject windowbg2">
						<div>
							<span>
								<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
									', $entry['name'], ' ', $entry['status'] == 'wip' ? '<span class="smalltext" style="color:#E00000">(' . $entry['progress'] . ')</span>' : '', '
								</a>
							</span>
							<p>', $entry['shortdesc'], '</p>
						</div>
					</td>';
					
			// The status...
			echo '
					<td class="stats windowbg">
						<a href="', $scripturl, '?action=bugtracker;sa=viewstatus;status=', $entry['status'], '">', $txt['status_' . $entry['status']], '</a>
					</td>';
			
			// Type?
			echo '
					<td class="stats windowbg2">
						<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $entry['type'], '">', $txt['bugtracker_' . $entry['type']], '</a>
					</td>';
			
			// And project!
			echo '
					<td class="stats windowbg">
						', !empty($entry['project']) ? '<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">' . $entry['project']['name'] . '</a>' : $txt['na'], '
					</td>';
					
			// Close this entry -- up to the next!
			echo '
				</tr>';
		}
		echo '
			</tbody>
		</table>
	</div><br />';
	}

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
	<span class="centertext"><a href="', $scripturl, '?action=bugtracker;sa=admin">', $txt['bt_acp'], '</a></span>
	<br class="clear" />';
}

?>