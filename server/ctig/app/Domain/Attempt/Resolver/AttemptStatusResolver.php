<?php

namespace App\Domain\Attempt\Resolver;

use App\Enums\AttemptStatus;
use App\Models\Attempt;

class AttemptStatusResolver{
    public function execute(Attempt $attempt):AttemptStatus{
        if($attempt->isBanned()){
            return AttemptStatus::Banned;
        }
        if($attempt->isFinished()){
            return AttemptStatus::Finished;
        }
        if($attempt->isStarted()){
            return AttemptStatus::Active;
        }
        //checked??

        return AttemptStatus::Pending;
    }
}