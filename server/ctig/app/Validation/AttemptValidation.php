<?php

namespace App\Validation;

use App\Exceptions\BusinessException;
use App\Models\Attempt;

class AttemptValidation{
    public function ensureNotBanned(Attempt $attempt){
        if($attempt->isExpired()){
            throw new BusinessException('Время попытки вышло');
        }
    }

    public function ensureActive(Attempt $attempt){
        if(!$attempt->isActive()){
            throw new BusinessException('Попытка неактивна');
        }
    }

    public function ensureNotExpired(Attempt $attempt){
        if($attempt->isExpired()){
            throw new BusinessException('Время попытки вышло');
        }
    }
}