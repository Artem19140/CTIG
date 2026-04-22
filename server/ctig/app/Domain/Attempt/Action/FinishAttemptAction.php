<?php

namespace App\Domain\Attempt\Action;

use App\Models\Attempt;
use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;

class FinishAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard,
        protected ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswers,
        protected CheckPassingThresholdAction $checkPassingThreshold,
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute(Attempt $attempt):void{
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        //$this->attemptGuard->ensureAcive($attempt);
        //Не завершать завершенные и проверенные

        DB::transaction(function() use($attempt){
            $attempt->finish();
            
            $this->zeroEmptyAutoAnswers->execute($attempt);
            if(!$attempt->hasUncheckedAnswers()){
                $this->finilizeAttemptCheckingAction->execute($attempt);
            }
            $attempt->save();
        });
        
    }
}