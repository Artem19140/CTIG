<?php

namespace App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;
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
        $this->canFinish($attempt);
        DB::transaction(function() use($attempt){
            $attempt->finish();
            
            //$this->zeroEmptyAutoAnswers->execute($attempt);
            
            if($attempt->canBeAutomaticallyFinalized()){
                $this->finilizeAttemptCheckingAction->execute($attempt);
            }
            $attempt->save();
        });
        
    }

    protected function canFinish(Attempt $attempt){
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        $this->attemptGuard->ensureActive($attempt, 'Завершить возможно только активную попытку');

        $minTimeMinutes = Attempt::MIN_TIME_FROM_START_TO_FINISH_MINUTES;
        $tooEarlyToFinish  = Carbon::now($attempt->time_zone)->lt($attempt->started_at->addMinutes($minTimeMinutes));
        if($tooEarlyToFinish){
            throw new BusinessException("Попытку возможно завершить минимум через  $minTimeMinutes минут после начала");
        }
    }
}