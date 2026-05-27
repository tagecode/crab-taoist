<?php

namespace Support\Http;

class JsonResponse
{
    public static function success($data = array(), $message = 'success', $code = 200, $httpStatus = 200)
    {
        return self::make($code, $message, $data, $httpStatus);
    }

    public static function error($message = 'error', $code = 500, $data = array(), $httpStatus = 500)
    {
        return self::make($code, $message, $data, $httpStatus);
    }

    public static function paginate($items, $page, $pageSize, $total)
    {
        return self::success(array(
            'items' => $items,
            'pagination' => array(
                'page' => (int) $page,
                'page_size' => (int) $pageSize,
                'total' => (int) $total,
            ),
        ));
    }

    public static function make($code, $message, $data, $httpStatus)
    {
        if (!headers_sent()) {
            http_response_code($httpStatus);
            header('Content-Type: application/json; charset=utf-8');
        }

        return json_encode(array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'request_id' => isset($_SERVER['HTTP_X_REQUEST_ID']) ? $_SERVER['HTTP_X_REQUEST_ID'] : '',
        ), JSON_UNESCAPED_UNICODE);
    }
}
