<?php

use Support\Config;
use Support\Http\JsonResponse;
use Support\Logging\LoggerFactory;
use Support\Yaf\ControllerBase;

class V1_HealthController extends ControllerBase
{
    public function indexAction()
    {
        $this->getResponse()->clearBody();

        LoggerFactory::info('health check', array(
            'status' => 'ok',
            'version' => Config::get('app.version', '0.1.0'),
        ));

        echo JsonResponse::success(array(
            'status' => 'ok',
            'version' => Config::get('app.version', '0.1.0'),
            'timestamp' => time(),
        ));

        return false;
    }
}
