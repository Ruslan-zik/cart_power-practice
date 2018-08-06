<?php

$schema['call_requests'] = array(
    'permissions' => array(
    	'GET' => 'view_call_requests', 
    	'POST' => 'manage_call_requests'
    ),
    'modes' => array(
        'delete' => array(
            'permissions' => 'manage_call_requests'
        )
    ),
);

$schema['tools']['modes']['update_status']['param_permissions']['table']['call_requests'] = 'manage_call_requests';

return $schema;
