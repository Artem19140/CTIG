<?php

namespace App\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    public function __construct(string $message = "Не найдено", int $code = 403){
        parent::__construct($message, $code);
    }
}
