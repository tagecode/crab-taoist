<?php

use Support\Env;

return array(
    'redis' => array(
        'host' => Env::get('REDIS_HOST', '127.0.0.1'),
        'port' => Env::get('REDIS_PORT', '6379'),
        'password' => Env::get('REDIS_PASSWORD', ''),
        'database' => Env::get('REDIS_DATABASE', '0'),
    ),
);
