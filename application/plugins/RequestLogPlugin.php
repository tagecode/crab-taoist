<?php

use Support\Logging\LoggerFactory;
use Support\Yaf\PluginBase;

class RequestLogPlugin extends PluginBase
{
    private $startedAt;

    public function routerStartup($request, $response)
    {
        $this->startedAt = microtime(true);
    }

    public function dispatchLoopShutdown($request, $response)
    {
        $duration = $this->startedAt ? round((microtime(true) - $this->startedAt) * 1000, 2) : 0;

        LoggerFactory::get('request')->info('request handled', array(
            'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI',
            'uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
            'duration_ms' => $duration,
            'request_id' => isset($_SERVER['HTTP_X_REQUEST_ID']) ? $_SERVER['HTTP_X_REQUEST_ID'] : '',
        ));
    }
}
