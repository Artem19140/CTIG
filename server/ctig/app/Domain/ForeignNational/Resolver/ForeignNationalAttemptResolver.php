<?php

namespace App\Domain\ForeignNational\Resolver;

use App\Exceptions\Attempt\AttemptBannedException;
use App\Exceptions\Attempt\AttemptExpiredException;
use App\Exceptions\Attempt\AttemptFinishedException;
use App\Models\ForeignNational;


class ForeignNationalAttemptResolver{
    public function execute(ForeignNational $foreignNational){

        $attempt = $foreignNational->latestAttempt;
            
        if(!$attempt){
            return null;
        }

        if($attempt->isBanned()){
            throw new AttemptBannedException();
        }

        if($attempt->isFinished()){
            throw new AttemptFinishedException();
        }

        if($attempt->isExpired()){
            throw new AttemptExpiredException();
        }

        if($attempt->isPending()){
            return redirect()->route('attempts.preparing', ['attempt' => $attempt]);
        }

        return redirect()->route('attempts.show', ['attempt' => $attempt]);
    }

}