<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct($message)
    {
        $this->message = json_encode($message);
    }
}