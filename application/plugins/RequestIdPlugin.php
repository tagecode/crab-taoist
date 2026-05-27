<?php

use Support\Yaf\PluginBase;

class RequestIdPlugin extends PluginBase
{
    public function routerStartup($request, $response)
    {
        if (empty($_SERVER['HTTP_X_REQUEST_ID'])) {
            $_SERVER['HTTP_X_REQUEST_ID'] = bin2hex(openssl_random_pseudo_bytes(8));
        }
    }
}
