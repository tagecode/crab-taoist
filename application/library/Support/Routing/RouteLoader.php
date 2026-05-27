<?php

namespace Support\Routing;

use Support\Yaf\RouteFactory;

class RouteLoader
{
    public static function load($router, $path)
    {
        if (!is_file($path)) {
            return;
        }

        $routes = require $path;
        foreach ($routes as $definition) {
            if (!isset($definition['type']) || $definition['type'] !== 'rewrite') {
                continue;
            }

            $route = RouteFactory::createRewrite($definition['match'], $definition['route']);
            $router->addRoute($definition['name'], $route);
        }
    }

    public static function all($path)
    {
        return is_file($path) ? require $path : array();
    }
}
