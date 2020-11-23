<?php

namespace App\Application\Exception;

use Throwable;

class AuthorizationException extends \Exception
{
    public function __construct($message = "Unauthorized", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
