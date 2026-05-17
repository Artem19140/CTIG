<?php

namespace App\Domain\Attempt\Guard;

use App\Exceptions\Attempt\AttemptBannedException;
use App\Exceptions\BusinessException;
use App\Models\Attempt;

class AttemptGuard{
    public function ensureNotBanned(Attempt $attempt, string | null $message = null):void{
        if($attempt->isBanned()){
            throw new AttemptBannedException($message ?? 'Попытка аннулирована');
        }
    }

    public function ensureStarted(Attempt $attempt, string $message = 'Попытка не начата'):void{
        if(!$attempt->isStarted()){
            throw new BusinessException($message);
        }
    }

}