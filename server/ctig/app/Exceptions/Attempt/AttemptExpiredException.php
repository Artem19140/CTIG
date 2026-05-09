<?php

namespace App\Exceptions\Attempt;

class AttemptExpiredException extends BaseAttemptException
{
    protected $message = 'Время попытки истекло';
}
