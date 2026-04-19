<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Models\AttemptAnswer;
use App\Models\User;
use Carbon\Carbon;
use DB;
use App\Domain\Attempt\Guard\AttemptGuard;
use Illuminate\Validation\ValidationException;

class RateAttemptAnswerAction{
    public function __construct(
        protected AttemptGuard $attemptGuard,
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute(AttemptAnswer $attemptAnswer, int $mark, User $user){
        abort(403);
        $attempt = $attemptAnswer->attempt;
        $this->attemptGuard->ensureNotBanned($attempt);
        //$this->attemptGuard->ensureFinished($attempt, 'Оценить можно только завершенную попытку');

        if($attemptAnswer->isChecked()){
            throw ValidationException::withMessages([
                'mark' => 'Задание уже проверено и оценено'
            ]);
        }

        $attemptAnswer->load(['taskVariant.task']);

        $task = $attemptAnswer->taskVariant->task;

        if($task->autoCheck()){
            throw ValidationException::withMessages([
                'mark' => 'Задание проверяется автоматически'
            ]);
        }

        if($task->mark < $mark){
            throw ValidationException::withMessages([
                'mark' => 'Выставленный балл больше, чем максимально возможный'
            ]);
        }

        DB::transaction(function () use($attemptAnswer, $attempt, $mark , $user) {
            $attemptAnswer->mark = $mark;
            $attemptAnswer->is_checked = true;
            $attemptAnswer->checked_at = Carbon::now($attempt->time_zone);
            $attemptAnswer->checked_by_id = $user->id;
            $attemptAnswer->save();

            if(!$attempt->hasUncheckedAnswers()){
                $this->finilizeAttemptCheckingAction->execute($attempt);
            }
        });
        return $attemptAnswer;
    }
}