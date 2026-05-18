<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Enums\TaskType;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RateAttemptAnswerAction{
    public function __construct(
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute(
        AttemptAnswer $attemptAnswer, 
        int $mark
        ):AttemptAnswer{
            $attemptAnswer->loadMissing(['taskVariant.task', 'attempt']);
            $task = $attemptAnswer->taskVariant->task;
            $attempt = $attemptAnswer->attempt;

            if($task->type !== TaskType::Speaking){
                $this->ensureAttemptFinished($attempt);
            }

            $this->ensureAttemptNotChecked($attempt);
            $this->ensureTaskIsNotAutoCheck($task);
            $this->ensureMarkIsValid($mark, $task);

            $this->rate($attemptAnswer, $mark);

            return $attemptAnswer;
    }

    protected function rate(
        AttemptAnswer $attemptAnswer,
        int $mark,

    ):void{
        $attemptAnswer->mark = $mark;
        $attemptAnswer->checked_at = Carbon::now();
        $attemptAnswer->save();
    }

    protected function ensureAttemptNotChecked(Attempt $attempt){
        if($attempt->checked_at !== null){
            $this->log([
                'reason' => 'trying to manual check answer, where attempt is already checked',
            ]);
            throw  ValidationException::withMessages([
                'mark' => 'Попытка уже проверена, оценка недоступна'
            ]);
        }
    }

    protected function ensureTaskIsNotAutoCheck(Task $task):void{
        if($task->autoCheck()){
            $this->log([
                'reason' => 'trying to manual check answer, where task with auto checking type'
            ]);
            throw  ValidationException::withMessages([
                'mark' => 'Задание проверяется автоматически'
            ]);
        }
    }

    protected function ensureMarkIsValid(int $mark, Task $task):void{
        if($task->mark < $mark){
            $this->log([
                'reason' => 'recieved mark more than max mark',
                'mark' => $mark,
                'max_mark' => $task->mark
            ]);
            throw  ValidationException::withMessages([
                'mark' => 'Выставленный балл больше чем максимально возможный'
            ]);
        }
    }

    protected function ensureAttemptFinished(Attempt $attempt):void{
        if(!$attempt->isFinished() && !$attempt->isBanned()){
            $this->log([
                'reason' => 'trying to rate answer with not finished attempt',
                'attempt_status' => $attempt->status
            ]);
            throw  ValidationException::withMessages([
                'mark' => 'Задание возможно оценить только при завершенной попытке'
            ]);
        }
    }

    protected function log(array $context):void{
        Log::warning('Manual attempt checking', [
            'attempt_answer_id' => request()->route('attemptAnswer')?->id,
            ...$context
        ]);
    }
}