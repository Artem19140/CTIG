<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Domain\Attempt\Action\ZeroEmptyAutoCheckAnswersAction;
use App\Enums\TaskType;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Task;
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

        if($task->type !== TaskType::Speaking){
            $this->attemptGuard->ensureFinished($attempt, 'Данный тип задания возможно оценить только при завершенной попытке');
        }

        $this->ensureAttemptNotChecked($attempt);
        $this->ensureTaskIsNotAutoCheck($task);
        $this->ensureMarkIsValid($mark, $task);

        $this->rate($attempt, $attemptAnswer, $mark, $user);

        return $attemptAnswer;
    }

    protected function rate(
        Attempt $attempt,
        AttemptAnswer $attemptAnswer,
        int $mark,
        User $user
    ):void{
        $attemptAnswer->mark = $mark;
        $attemptAnswer->checked_at = Carbon::now();
        $attemptAnswer->checked_by_id = $user->id;
        $attemptAnswer->save();
    }

    protected function ensureAttemptNotChecked(Attempt $attempt){
        if($attempt->isChecked()){
            throw ValidationException::withMessages([
                'mark' => 'Попытка уже проверена, оценка недоступна'
            ]);
        }
    }

    protected function ensureTaskIsNotAutoCheck(Task $task){
        if($task->autoCheck()){
            throw ValidationException::withMessages([
                'mark' => 'Задание проверяется автоматически'
            ]);
        }
    }

    protected function ensureMarkIsValid(int $mark, Task $task){
        if($task->mark < $mark){
            throw ValidationException::withMessages([
                'mark' => 'Выставленный балл больше, чем максимально возможный'
            ]);
        }
    }
}