<?php

namespace Support;

use Dotenv\Dotenv;

class Env
{
    private static $loaded = false;

    public static function load($basePath)
    {
        if (self::$loaded) {
            return;
        }

        if (is_file($basePath . '/.env')) {
            $dotenv = new Dotenv($basePath);
            $dotenv->load();
        }

        self::$loaded = true;
    }

    public static function get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false && isset($_ENV[$key])) {
            $value = $_ENV[$key];
        }

        if ($value === false && isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        }

        if ($value === false) {
            return $default;
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return $value;
    }
}
