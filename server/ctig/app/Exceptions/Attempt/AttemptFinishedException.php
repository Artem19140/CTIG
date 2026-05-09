<?php

namespace App\Exceptions\Attempt;


class AttemptFinishedException extends BaseAttemptException
{
    protected $message = 'Попытка завершена';
}
