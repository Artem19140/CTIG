<?php

namespace App\Exceptions;

class BusinessException extends BaseException
{
    public function __construct(string $message = ""){
        parent::__construct(400,$message);
    }
}
