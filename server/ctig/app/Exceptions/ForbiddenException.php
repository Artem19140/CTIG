<?php

namespace App\Exceptions;


class ForbiddenException extends BaseException
{
    public function __construct(string $message = "Не найдено"){
        parent::__construct(403, $message);
    }
}
