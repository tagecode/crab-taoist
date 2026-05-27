<?php

namespace Support\Exceptions;

class ApiException extends \Exception
{
    private $httpStatus;
    private $payload;

    public function __construct($message, $code = 500, $httpStatus = 500, $payload = array())
    {
        parent::__construct($message, $code);
        $this->httpStatus = $httpStatus;
        $this->payload = $payload;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
