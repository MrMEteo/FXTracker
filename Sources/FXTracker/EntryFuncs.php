<?php

function grabEntry($id)
{
        global $context, $smcFunc, $user_profile;
        
        // No id? No data.
        if (empty($id))
                return false;
        
        // Try to load everything from this entry.
        $result = $smcFunc['db_query']('', '
                SELECT
                        id, name, description,
                        type, tracker, private,
                        startedon, project, status,
                        attention, progress, in_trash
                FROM {db_prefix}bugtracker_entries
                WHERE id = {int:id}
                LIMIT 1',
                array(
                        'id' => $id,
                )
        );
        
        // Nothing? Poor, just poor...
        if ($smcFunc['db_num_rows']($result) == 0)
                return false;
        
        // Fetch the data, now.
        $data = $smcFunc['db_fetch_assoc']($result);
        
        // Load the user data.
        if (!loadMemberData($data['tracker']))
                $user_profile[$data['tracker']] = array('id_member' => $data['tracker']);
        
        // Filter it.
        $fdata = array(
                'id' => $data['id'],
                'name' => $data['name'],
                'description' => parse_bbc($description),
                'type' => $data['type'],
                'tracker' => $user_profile[$data['tracker']],
                'private' => (int) $data['private'],
                'startedon' => timeformat($data['startedon']),
                'project' => (int) $data['project'],
                'status' => $data['status'],
                'attention' => (int) $data['attention'],
                'progress' => (int) $data['progress'],
                'in_trash' => (int) $data['in_trash'],
        );
        
        // And this is our entry.
        return $fdata;
}

function grabEntries($project = '', $order = 'DESC')
{
        global $context, $smcFunc;
}