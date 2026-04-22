<?php

namespace App\Domain\Attempt\Guard;

use App\Exceptions\BusinessException;
use App\Models\Attempt;

class AttemptGuard{
    public function ensureNotBanned(Attempt $attempt){
        if($attempt->isBanned()){
            throw new BusinessException('Попытка аннулирована');
        }
    }

    public function ensureStarted(Attempt $attempt, string $message = 'Попытка не начата'){
        if(!$attempt->isStarted()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotStarted(Attempt $attempt, string $message = 'Попытка уже началась'){
        if($attempt->isStarted()){
            throw new BusinessException($message);
        }
    }

    public function ensureActive(Attempt $attempt, string $message = 'Попытка неактивна'){
        if(!$attempt->isStarted() && $attempt->isFinished()){
            throw new BusinessException($message);
        }
    }

    public function ensureFinished(Attempt $attempt, string $message='Попытка еще не завершена'){
        if(!$attempt->isFinished()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotFinished(Attempt $attempt, string $message='Попытка завершена'){
        if($attempt->isFinished()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotExpired(Attempt $attempt){
        if($attempt->isExpired()){
            throw new BusinessException('Время попытки вышло');
        }
    }
}