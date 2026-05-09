<?php

namespace App\Exceptions\Attempt;

class AttemptBannedException extends BaseAttemptException
{
    protected $message = 'Попытка аннулирована';
}
