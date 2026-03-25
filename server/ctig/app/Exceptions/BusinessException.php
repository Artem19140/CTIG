<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BusinessException extends BaseException
{
    public function __construct(string $message = ""){
        parent::__construct(400,$message);
    }
}
