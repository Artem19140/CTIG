<?php

namespace App\Exceptions\Attempt;

class AttemptExpiredException extends BaseAttemptException
{
    public $message = 'Время попытки истекло';
}
