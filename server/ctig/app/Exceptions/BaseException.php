<?php

namespace App\Exceptions;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseException extends Exception
{
    public function __construct(int $code, string $message = ""){
        parent::__construct($message, $code);
        //parent::__construct($code, $message);
    }

}
