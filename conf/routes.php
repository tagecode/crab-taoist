<?php

return array(
    array(
        'name' => 'health',
        'type' => 'rewrite',
        'match' => '/health',
        'route' => array(
            'module' => 'Api',
            'controller' => 'V1_Health',
            'action' => 'index',
        ),
        'methods' => array('GET'),
        'auth' => false,
    ),
    array(
        'name' => 'api.v1.ping',
        'type' => 'rewrite',
        'match' => '/v1/ping',
        'route' => array(
            'module' => 'Api',
            'controller' => 'V1_Ping',
            'action' => 'index',
        ),
        'methods' => array('GET'),
        'auth' => false,
    ),
);
