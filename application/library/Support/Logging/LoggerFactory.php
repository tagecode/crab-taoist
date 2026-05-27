<?php

namespace Support\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Support\Config;

class LoggerFactory
{
    private static $loggers = array();

    public static function boot($basePath)
    {
        $path = Config::get('logging.path', $basePath . '/runtime/logs');
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    public static function get($channel = 'app')
    {
        if (isset(self::$loggers[$channel])) {
            return self::$loggers[$channel];
        }

        $logger = new Logger($channel);
        $path = Config::get('logging.path', BASE_PATH . '/runtime/logs');
        $logger->pushHandler(new StreamHandler($path . '/' . $channel . '.log', Logger::DEBUG));

        self::$loggers[$channel] = $logger;
        return $logger;
    }

    public static function info($message, array $context = array())
    {
        self::get('app')->info($message, $context);
    }

    public static function error($message, array $context = array())
    {
        self::get('error')->error($message, $context);
    }
}
