<?php

namespace Support\Yaf;

class RouteFactory
{
    public static function createRewrite($match, array $route)
    {
        if (class_exists('Yaf\\Route\\Rewrite', false)) {
            return new \Yaf\Route\Rewrite($match, $route);
        }

        return new \Yaf_Route_Rewrite($match, $route);
    }
}
