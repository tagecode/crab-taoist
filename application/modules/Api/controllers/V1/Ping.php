<?php

use Support\Http\JsonResponse;
use Support\Yaf\ControllerBase;

class V1_PingController extends ControllerBase
{
    public function indexAction()
    {
        $this->getResponse()->clearBody();
        echo JsonResponse::success(array('pong' => true));
        return false;
    }
}
