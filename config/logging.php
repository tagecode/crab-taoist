<?php

use Support\Env;

// Monolog 通道：request（每请求）、error（5xx/异常）、app（应用事件，见 LoggerFactory::info）
return array(
    'channel' => Env::get('LOG_CHANNEL', 'daily'),
    'level' => Env::get('LOG_LEVEL', 'debug'),
    'path' => BASE_PATH . '/' . Env::get('LOG_PATH', 'runtime/logs'),
);
