<?php

namespace Support\Exceptions;

class ValidationException extends ApiException
{
    public function __construct($message, $errors = array())
    {
        parent::__construct($message, 422, 422, array('errors' => $errors));
    }
}
