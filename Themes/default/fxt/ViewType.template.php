<?php

/* FXTracker ViewType Template */

function template_TrackerViewType()
{
	global $context, $scripturl, $txt, $settings;

	// Headers and starting the table; nothing interesting
	echo '
	<div class="tborder topic_table">
		<table class="table_grid fullwidth" cellspacing="0">
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
	
	foreach ($context['bugtracker']['entries'] as $entry)
	{	
			echo '
				<tr>';
				
			// Status icons
			echo '
					<td class="icon1 windowbg">
						<img src="', $settings['images_url'], '/bugtracker/', $entry['type'], '.png" alt="" />
					</td>
					<td class="icon2 windowbg">
						<img src="' . $settings['images_url'] . '/bugtracker/', $entry['status'] == 'wip' ? 'wip.gif' : $entry['status'] . '.png', '" alt="" />
					</td>';
					
			// Entry name and description
			echo '
					<td class="subject windowbg2">
						<div>
							<span>
								<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
									', $entry['name'], ' ', $entry['status'] == 'wip' ? '<span class="smalltext progress">(' . $entry['progress'] . ')</span>' : '', ' 
								</a>
							</span>
							<p>', $entry['shortdesc'], '</p>
						</div>
					</td>';
					
			// Status and type
			echo '
					<td class="stats windowbg">
						<a href="', $scripturl, '?action=bugtracker;sa=viewstatus;status=', $entry['status'], '">', $txt['status_' . $entry['status']], '</a>
					</td>
					<td class="stats windowbg2">
						<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $entry['type'], '">', $txt['bugtracker_' . $entry['type']], '</a>
					</td>';
					
			// Last but not least, the project.
			echo '
					<td class="stats windowbg">
						', !empty($entry['project']) ? '<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">' . $entry['project']['name'] . '</a>' : $txt['na'], '
					</td>';
					
			echo '
				</tr>';
	}
	echo '
			</tbody>
		</table>';

	// Got nothing? Start crying, bud'.
	if (empty($context['bugtracker']['entries']))
		echo '<div class="centertext windowbg2 halfpadding">', $txt['no_items'], '</div>';

	// How many items do we have which require attention?
	echo '
	</div><br />
	<div class="title_barIC">
		<h4 class="titlebg">
			', sprintf($txt['items_attention'], count($context['bugtracker']['attention'])), '
		</h4>
	</div>';
	// Show 'em!
	if (!empty($context['bugtracker']['attention']))
	{
		echo '
	<div class="tborder topic_table">
		<table class="table_grid fullwidth" cellspacing="0">
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
		foreach ($context['bugtracker']['attention'] as $entry)
		{
			echo '
				<tr>';
				
			// Status icons
			echo '
					<td class="icon1 windowbg">
						<img src="', $settings['images_url'], '/bugtracker/', $entry['type'], '.png" alt="" />
					</td>
					<td class="icon2 windowbg">
						<img src="' . $settings['images_url'] . '/bugtracker/', $entry['status'] == 'wip' ? 'wip.gif' : $entry['status'] . '.png', '" alt="" />
					</td>';
					
			// Entry name and description
			echo '
					<td class="subject windowbg2">
						<div>
							<span>
								<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
									', $entry['name'], ' ', $entry['status'] == 'wip' ? '<span class="smalltext progress">(' . $entry['progress'] . ')</span>' : '', ' 
								</a>
							</span>
							<p>', $entry['shortdesc'], '</p>
						</div>
					</td>';
					
			// Status and type
			echo '
					<td class="stats windowbg">
						<a href="', $scripturl, '?action=bugtracker;sa=viewstatus;status=', $entry['status'], '">', $txt['status_' . $entry['status']], '</a>
					</td>
					<td class="stats windowbg2">
						<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $entry['type'], '">', $txt['bugtracker_' . $entry['type']], '</a>
					</td>';
					
			// Last but not least, the project.
			echo '
					<td class="stats windowbg">
						', !empty($entry['project']) ? '<a href="' . $scripturl . '?action=bugtracker;sa=projectindex;project=' . $entry['project']['id'] . '">' . $entry['project']['name'] . '</a>' : $txt['na'], '
					</td>';
					
			echo '
				</tr>';
		}
		echo '
		</table>
	</div>';
	}

	echo '
	<br class="clear" />';
}

?>