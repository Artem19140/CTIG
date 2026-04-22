<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Domain\Attempt\Action\ZeroEmptyAutoCheckAnswersAction;
use App\Enums\TaskType;
use App\Models\AttemptAnswer;
use App\Models\User;
use Carbon\Carbon;
use DB;
use App\Domain\Attempt\Guard\AttemptGuard;
use Illuminate\Validation\ValidationException;

class RateAttemptAnswerAction{
    public function __construct(
        protected AttemptGuard $attemptGuard,
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction,
        protected ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswersAction
    ){}
    public function execute(AttemptAnswer $attemptAnswer, int $mark, User $user){
        $attemptAnswer->loadMissing(['taskVariant.task', 'attempt']);
        $task = $attemptAnswer->taskVariant->task;
        $attempt = $attemptAnswer->attempt;
        if(!$task->type === TaskType::Speaking){
            $this->attemptGuard->ensureFinished($attempt, 'Данный тип задания возможно оценить только при завершенной попытке');
        }

        if($attempt->isChecked()){
            throw ValidationException::withMessages([
                'mark' => 'Попытка уже проверена, оценка недоступна'
            ]);
        }

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

        DB::transaction(function () use($attemptAnswer,$mark , $user, $attempt) {
            $attemptAnswer->mark = $mark;
            $attemptAnswer->is_checked = true;
            $attemptAnswer->checked_at = Carbon::now($attempt->time_zone);
            $attemptAnswer->checked_by_id = $user->id;
            $attemptAnswer->save();

            // if(!$attempt->hasUncheckedAnswers()){
            //     $this->zeroEmptyAutoAnswersAction->execute($attempt);
            //     $this->finilizeAttemptCheckingAction->execute($attempt);
            // }
        });
        return $attemptAnswer;
    }
}