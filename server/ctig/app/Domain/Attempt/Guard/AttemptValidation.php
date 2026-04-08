<?php

namespace Server\Ctig\App\Domain\Attempt\Guard;

use App\Exceptions\BusinessException;
use App\Models\Attempt;

class AttemptGuard{
    public function ensureNotBanned(Attempt $attempt){
        if($attempt->isBanned()){
            throw new BusinessException('Попытка аннулирована');
        }
    }

    public function ensureActive(Attempt $attempt){
        if(!$attempt->isActive()){
            throw new BusinessException('Попытка неактивна');
        }
    }

    public function ensurePending(Attempt $attempt, string $message = 'Попытка '){
        if(!$attempt->isActive()){
            throw new BusinessException($message);
        }
    }

    public function ensureFinished(Attempt $attempt, string $message='Попытка еще не завершена'){
        if(!$attempt->isFinished()){
            throw new BusinessException($message);
        }
    }

    public function ensureNotExpired(Attempt $attempt){
        if($attempt->isExpired()){
            throw new BusinessException('Время попытки вышло');
        }
    }
}