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

	echo '
		</ul>
	</div><br />
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
					<th scope="col" width="18%" class="last_th">
						', $txt['type'], '
					</th>
				</tr>
			</thead>
			<tbody>';

	foreach ($context['bugtracker']['entries'] as $entry)
	{
		echo '
				<tr>
					<td class="icon1 windowbg">
						<img src="', $settings['images_url'], '/bugtracker/', $entry['type'], '.png" alt="" />
					</td>
					<td class="icon2 windowbg">
						', $entry['attention'] ? '<img src="' . $settings['images_url'] . '/bugtracker/attention.png" alt="" /><span class="iconslash">/</span>' : '', '<img src="' . $settings['images_url'] . '/bugtracker/', $entry['status'] == 'wip' ? 'wip.gif' : $entry['status'] . '.png', '" alt="" />
					</td>
					<td class="subject windowbg2">
						<div>
							<span>
								<a href="', $scripturl, '?action=bugtracker;sa=view;entry=', $entry['id'], '">
									', $entry['name'], '
								</a> ', $entry['status'] == 'wip' ? '<span class="smalltext progress">(' . $entry['progress'] . ')</span>' : '', '
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
				</tr>';
	}
	echo '
			</tbody>
		</table>';

	if (empty($context['bugtracker']['entries']))
		echo '<div class="centertext windowbg2 halfpadding">', $txt['no_items'], '</div>';

	echo '
	</div><br />
	<div class="title_bar">
		<h3 class="titlebg">
			', sprintf($txt['items_attention'], count($context['bugtracker']['attention'])), '
		</h3>
	</div>';
	if (count($context['bugtracker']['attention']) != 0)
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
					<th scope="col" width="18%" class="last_th">
						', $txt['type'], '
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
									', $entry['name'], ' ', $entry['status'] == 'wip' ? '<span class="smalltext progress">(' . $entry['progress'] . ')</span>' : '', ' 
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
