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
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        $this->attemptGuard->ensureActive($attempt, 'Завершить возможно только активную попытку');
        //Не завершать завершенные и проверенные
        $tenMinutesPassed = Carbon::now($attempt->time_zone)->gt($attempt->started_at->addMinutes(10));
        if(!$tenMinutesPassed){
            throw new BusinessException('Попытку возможно завершить минимум через  10 минут после начала');
        }
        throw new BusinessException('Попытку возможно завершить минимум через  10 минут после начала');
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