<?php

namespace Support\Yaf;

class ApplicationFactory
{
    public static function create($iniPath, $environment)
    {
        if (class_exists('Yaf\\Application', false)) {
            return new \Yaf\Application($iniPath, $environment);
        }

        return new \Yaf_Application($iniPath, $environment);
    }
}
