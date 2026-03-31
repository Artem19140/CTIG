<?php

namespace App\Actions\Attempt;

use App\Exceptions\BusinessException;
use App\Models\Attempt;

class EnsureAttemptIsNotBannedAction{
    public function execute(Attempt $attempt){
        if($attempt->isExpired()){
            throw new BusinessException('Время попытки вышло');
        }
    }
}