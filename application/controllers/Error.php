<?php

use Support\Exceptions\YafExceptionResolver;
use Support\Http\JsonResponse;
use Support\Logging\LoggerFactory;
use Support\Yaf\ControllerBase;

class ErrorController extends ControllerBase
{
    public function errorAction($exception)
    {
        $this->getResponse()->clearBody();

        $debug = \Support\Config::get('app.debug', false);
        $resolved = YafExceptionResolver::resolve($exception, $debug);

        if ($resolved['logLevel'] === 'info') {
            LoggerFactory::info($exception->getMessage(), array(
                'exception' => get_class($exception),
                'http_status' => $resolved['httpStatus'],
            ));
        } else {
            LoggerFactory::error($exception->getMessage(), array(
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ));
        }

        echo JsonResponse::error(
            $resolved['message'],
            $resolved['code'],
            $resolved['data'],
            $resolved['httpStatus']
        );

        return false;
    }
}
