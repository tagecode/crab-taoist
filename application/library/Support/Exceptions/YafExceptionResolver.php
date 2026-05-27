<?php

namespace Support\Exceptions;

class YafExceptionResolver
{
    /**
     * Yaf 在控制器/动作不存在时抛出 LoadFailed 异常（legacy 与 namespace 模式类名不同）。
     */
    public static function isNotFound(\Exception $exception)
    {
        $class = get_class($exception);
        if (strpos($class, 'LoadFailed') !== false) {
            return strpos($class, 'Controller') !== false
                || strpos($class, 'Action') !== false;
        }

        $message = $exception->getMessage();

        return stripos($message, 'Failed opening controller') !== false
            || stripos($message, 'Failed opening action') !== false;
    }

    /**
     * @return array{message:string,code:int,httpStatus:int,data:array,logLevel:string}
     */
    public static function resolve(\Exception $exception, $debug)
    {
        if ($exception instanceof ApiException) {
            return array(
                'message' => $exception->getMessage(),
                'code' => (int) $exception->getCode(),
                'httpStatus' => (int) $exception->getHttpStatus(),
                'data' => $exception->getPayload(),
                'logLevel' => 'error',
            );
        }

        if (self::isNotFound($exception)) {
            return array(
                'message' => 'Not Found',
                'code' => 404,
                'httpStatus' => 404,
                'data' => array(),
                'logLevel' => 'info',
            );
        }

        $message = '服务器内部错误';
        $code = 500;
        $httpStatus = 500;
        $data = array();

        if ($debug) {
            $message = $exception->getMessage();
            $data = array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            );
        }

        return array(
            'message' => $message,
            'code' => $code,
            'httpStatus' => $httpStatus,
            'data' => $data,
            'logLevel' => 'error',
        );
    }
}
