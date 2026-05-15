<?php

namespace App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class FinishAttemptAction{
    public function __construct(
        protected CheckPassingThresholdAction $checkPassingThreshold,
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute(Attempt $attempt):void{
        $this->canFinish($attempt);
        DB::transaction(function() use($attempt){
            $attempt->finish();
            if($attempt->canBeAutomaticallyFinalized()){
                $this->finilizeAttemptCheckingAction->execute($attempt);
            }
            $attempt->save();
        });
        
    }

    protected function canFinish(Attempt $attempt){

        $minTimeMinutes = Attempt::MIN_TIME_FROM_START_TO_FINISH_MINUTES;
        $now = Carbon::now();

        $attemptCanBeFinished = $attempt->started_at->copy()->addMinutes($minTimeMinutes);

        $tooEarlyToFinish = $now->lte($attemptCanBeFinished);
        if($tooEarlyToFinish){
            throw new BusinessException("Попытку возможно завершить минимум через  $minTimeMinutes минут после начала");
        }
    }
}