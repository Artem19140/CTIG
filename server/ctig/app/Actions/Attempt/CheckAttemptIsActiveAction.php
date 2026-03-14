<?php

namespace App\Actions\Attempt;

use App\Models\Attempt;

class CheckAttemptIsActiveAction{
    public function execute(Attempt $attempt):bool{
        if($attempt->isExpired() || !$attempt->isActive()){
            $attempt->finish();
            $attempt->save();
            return false;
        }
        return true;
    }
}