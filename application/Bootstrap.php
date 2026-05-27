<?php

use Support\Env;
use Support\Logging\LoggerFactory;
use Support\Routing\RouteLoader;
use Support\Yaf\BootstrapBase;

class Bootstrap extends BootstrapBase
{
    public function _initEnv($dispatcher)
    {
        Env::load(BASE_PATH);
        date_default_timezone_set(Env::get('APP_TIMEZONE', 'Asia/Shanghai'));
    }

    public function _initLogger($dispatcher)
    {
        LoggerFactory::boot(BASE_PATH);
    }

    public function _initRoutes($dispatcher)
    {
        RouteLoader::load($dispatcher->getRouter(), BASE_PATH . '/conf/routes.php');
    }

    public function _initPlugins($dispatcher)
    {
        $plugins = is_file(BASE_PATH . '/conf/plugins.php') ? require BASE_PATH . '/conf/plugins.php' : array();
        foreach ($plugins as $pluginClass) {
            $pluginFile = APPLICATION_PATH . '/plugins/' . $pluginClass . '.php';
            if (is_file($pluginFile)) {
                require_once $pluginFile;
            }

            if (class_exists($pluginClass, false)) {
                $dispatcher->registerPlugin(new $pluginClass());
            }
        }
    }
}
