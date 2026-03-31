<?php

namespace App\Actions\Attempt;

use App\Exceptions\BusinessException;
use App\Models\Attempt;

class EnsureAttemptIsActiveAction{
    public function execute(Attempt $attempt){
        if(!$attempt->isActive()){
            throw new BusinessException('Попытка неактивна');
        }
    }
}