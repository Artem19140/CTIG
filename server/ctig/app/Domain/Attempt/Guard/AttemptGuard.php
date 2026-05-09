<?php

namespace App\Domain\Attempt\Guard;

use App\Exceptions\Attempt\AttemptBannedException;
use App\Exceptions\Attempt\AttemptExpiredException;
use App\Exceptions\Attempt\AttemptFinishedException;
use App\Exceptions\BusinessException;
use App\Models\Attempt;

class AttemptGuard{
    public function ensureNotBanned(Attempt $attempt, string | null $message = null){
        if($attempt->isBanned()){
            throw new AttemptBannedException($message ?? 'Попытка аннулирована');
        }
    }

    public function ensureStarted(Attempt $attempt, string $message = 'Попытка не начата'){
        if(!$attempt->isStarted()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotFinished(Attempt $attempt, string $message='Попытка завершена'){
        if($attempt->isFinished()){
            throw new AttemptFinishedException($message);
        }
    }

    public function ensureNotExpired(Attempt $attempt){
        if($attempt->isExpired()){
            throw new AttemptExpiredException('Время попытки вышло');
        }
    }

    public function ensureAccessible(Attempt $attempt): void
    {
        $this->ensureNotBanned($attempt);
        $this->ensureNotFinished($attempt);
        $this->ensureNotExpired($attempt);
    }
}