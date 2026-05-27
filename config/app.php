<?php

use Support\Env;

return array(
    'name' => Env::get('APP_NAME', 'Crab Taoist'),
    'env' => Env::get('APP_ENV', 'local'),
    'debug' => Env::get('APP_DEBUG', false),
    'key' => Env::get('APP_KEY', ''),
    'timezone' => Env::get('APP_TIMEZONE', 'Asia/Shanghai'),
    'version' => Env::get('APP_VERSION', '0.1.0'),
);
