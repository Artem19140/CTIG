<?php

namespace App\Validation;

use App\Exceptions\BusinessException;
use App\Models\Attempt;

class AttemptValidation{
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

    public function ensureFinished(Attempt $attempt){
        if(!$attempt->isFinished()){
            throw new BusinessException('Попытка еще не завершена');
        }
    }

    public function ensureNotExpired(Attempt $attempt){
        if($attempt->isExpired()){
            throw new BusinessException('Время попытки вышло');
        }
    }
}