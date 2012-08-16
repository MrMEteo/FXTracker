<?php

function template_BTACPManageProjectsIndex()
{
        global $context, $txt, $scripturl;
        
        echo '
        <div class="cat_bar">
                <h3 class="catbg">
                        ', $txt['bugtracker_projects'], '
                </h3>
        </div>';
        
        // Just show the list already
        template_show_list('fxt_projects');
}