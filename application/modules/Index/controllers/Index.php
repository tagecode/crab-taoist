<?php

use Support\Http\JsonResponse;
use Support\Yaf\ControllerBase;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->getResponse()->clearBody();
        echo JsonResponse::success(array(
            'name' => 'Crab Taoist',
            'message' => 'API service is running',
        ));
        return false;
    }
}
