<?php

/* FXTracker Notes Template */

function template_TrackerEditNote()
{
        global $context, $txt, $scripturl;
        
        // Start with our famous header... I think.
        echo '
        <div class="cat_bar">
                <h3 class="catbg">
                        ', $txt['edit_note'], '
                </h3>
        </div>';
        
        // And start our form!
        echo '
        <form action="', $scripturl, '?action=bugtracker;sa=editnote2" method="post">';
        
        // Do a nice roundframe...
        echo '
        <div class="windowbg">
                <span class="topslice"><span></span></span>
                <div class="fullpadding">
                        <div id="bbcBox_message"></div>
			<div id="smileyBox_message"></div>', 
                        template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message'), '<br />
                        <input type="hidden" name="note_id" value="', $context['bugtracker']['note']['id'], '" />
                        <input type="hidden" name="is_fxt" value="true" />
                        <input type="submit" value="', $txt['entry_submit'], '" />
                        <div class="floatright">
                                ', sprintf(
                                        $txt['note_by'],
                                        $context['bugtracker']['note']['author']['member_name'],
                                        date('d-m-Y H:i:s', $context['bugtracker']['note']['time']),
                                        $scripturl . '?action=profile;u=' . $context['bugtracker']['note']['author']['id_member']
                                ), '
                        </div>
                </div>
                <span class="botslice"><span></span></span>
        </div>';
        
        // And close our form.
        echo '
        </form>
        <br class="clear" />';
        
        // That's it folks!
}

function template_TrackerAddNote()
{
        global $context, $txt, $scripturl;
        // Start with our famous header... I think.
        echo '
        <div class="cat_bar">
                <h3 class="catbg">
                        ', $txt['add_note'], '
                </h3>
        </div>';
        
        // And start our form!
        echo '
        <form action="', $scripturl, '?action=bugtracker;sa=addnote2" method="post">';
        
        // Do a nice roundframe...
        echo '
        <div class="windowbg">
                <span class="topslice"><span></span></span>
                <div class="fullpadding">
                        <div id="bbcBox_message"></div>
			<div id="smileyBox_message"></div>', 
                        template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message'), '<br />
                        <input type="hidden" name="entry_id" value="', $context['bugtracker']['note']['id'], '" />
                        <input type="hidden" name="is_fxt" value="true" />
                        <input type="submit" value="', $txt['entry_submit'], '" />
                </div>
                <span class="botslice"><span></span></span>
        </div>';
        
        // And close our form.
        echo '
        </form>
        <br class="clear" />';
}