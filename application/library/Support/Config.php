<?php

namespace Support;

class Config
{
    private static $items = array();

    public static function get($key, $default = null)
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);

        if (!isset(self::$items[$file])) {
            $path = BASE_PATH . '/config/' . $file . '.php';
            self::$items[$file] = is_file($path) ? require $path : array();
        }

        $value = self::$items[$file];
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
