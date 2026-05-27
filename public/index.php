<?php

define('BASE_PATH', dirname(__DIR__));
define('APPLICATION_PATH', BASE_PATH . '/application');

$autoload = BASE_PATH . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}

$environment = getenv('APPLICATION_ENV');
if ($environment === false && isset($_SERVER['APPLICATION_ENV'])) {
    $environment = $_SERVER['APPLICATION_ENV'];
}
if ($environment === false) {
    $environment = 'local';
}

$app = Support\Yaf\ApplicationFactory::create(BASE_PATH . '/conf/application.ini', $environment);
$app->bootstrap()->run();
