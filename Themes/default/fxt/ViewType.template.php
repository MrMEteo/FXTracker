<?php

/* FXTracker ViewType Template */

function template_TrackerViewType()
{
	global $context, $scripturl, $txt, $settings;

	if (!isset($_GET['status']))
		echo '
	<div class="buttonlist">
		<ul>
			<li>
				<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $context['bugtracker']['viewtype_type'] . (isset($_GET['viewclosed']) ? '' : ';viewclosed'), '">
					<span>', isset($_GET['viewclosed']) ? $txt['hideclosed'] : $txt['viewclosed'] . ' [' . $context['bugtracker']['num_closed'] . ']', '</span>
				</a>
			</li>
		</ul>
	</div><br />';

	echo '	<div class="tborder topic_table">
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
	$i = 0;
	foreach ($context['bugtracker']['entries'] as $entry)
	{
		if ($entry['status'] == 'done' && !isset($_GET['viewclosed']) && !isset($_GET['status']))
			continue;
		
		$i++;
		echo '
				<tr>
					<td class="icon1 windowbg">
						<img src="', $settings['images_url'], '/bugtracker/', $entry['type'], '.png" alt="" />
					</td>
					<td class="icon2 windowbg">
						', $entry['attention'] ? '<img src="' . $settings['images_url'] . '/bugtracker/attention.png" alt="" /><span style="font-size: 120%">/</span>' : '', '<img src="' . $settings['images_url'] . '/bugtracker/', $entry['status'] == 'wip' ? 'wip.gif' : $entry['status'] . '.png', '" alt="" />
					</td>
					<td class="subject windowbg2">
						<div>
							<span>
								<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
									', $entry['name'], '
								</a> ', $entry['status'] == 'wip' ? '<span class="smalltext" style="color:#E00000">(' . $entry['progress'] . ')</span>' : '', '
							</span>
							<p>', $entry['shortdesc'], '</p>
						</div>
					</td>
					<td class="stats windowbg">
						<a href="', $scripturl, '?action=bugtracker;sa=viewstatus;status=', $entry['status'], '">', $txt['status_' . $entry['status']], '</a>
					</td>
					<td class="stats windowbg2">
						<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $entry['type'], '">', $txt['bugtracker_' . $entry['type']], '</a>
					</td>
					<td class="stats windowbg">
						', !empty($entry['project']) ? '<a href="' . $scripturl . '?action=bugtracker;sa=viewproject;project=' . $entry['project']['id'] . '">' . $entry['project']['name'] . '</a>' : $txt['na'], '
					</td>
				</tr>';
	}
	echo '
		</table>';

	if ($i == 0)
		echo '<div class="centertext windowbg2" style="padding: 5px;">', $txt['no_items'], '</div>';

	echo '
	</div><br />
	<div class="title_barIC">
		<h4 class="titlebg">
			', sprintf($txt['items_attention'], count($context['bugtracker']['attention'])), '
		</h4>
	</div>';
	if (count($context['bugtracker']['attention']) != 0)
	{
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
		foreach ($context['bugtracker']['attention'] as $entry)
		{
			echo '
				<tr>
					<td class="icon1 windowbg">
						<img src="', $settings['images_url'], '/bugtracker/', $entry['type'], '.png" alt="" />
					</td>
					<td class="icon2 windowbg">
						<img src="' . $settings['images_url'] . '/bugtracker/', $entry['status'] == 'wip' ? 'wip.gif' : $entry['status'] . '.png', '" alt="" />
					</td>
					<td class="subject windowbg2">
						<div>
							<span>
								<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
									', $entry['name'], ' ', $entry['status'] == 'wip' ? '<span class="smalltext" style="color:#E00000">(' . $entry['progress'] . ')</span>' : '', '
								</a>
							</span>
							<p>', $entry['shortdesc'], '</p>
						</div>
					</td>
					<td class="stats windowbg">
						<a href="', $scripturl, '?action=bugtracker;sa=viewstatus;status=', $entry['status'], '">', $txt['status_' . $entry['status']], '</a>
					</td>
					<td class="stats windowbg2">
						<a href="', $scripturl, '?action=bugtracker;sa=viewtype;type=', $entry['type'], '">', $txt['bugtracker_' . $entry['type']], '</a>
					</td>
					<td class="stats windowbg">
						', !empty($entry['project']) ? '<a href="' . $scripturl . '?action=bugtracker;sa=viewproject;project=' . $entry['project']['id'] . '">' . $entry['project']['name'] . '</a>' : $txt['na'], '
					</td>
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