<?php

namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseException extends HttpException
{
    public function __construct(int $code, string $message = ""){
        parent::__construct($code, $message);
    }

}
