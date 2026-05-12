<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Enums\Event;
use App\Enums\Resource;
use App\Enums\TaskType;
use App\Exceptions\Attempt\AnswerManualCheckException;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Task;
use App\Support\Log\LogActivity;
use Carbon\Carbon;
use App\Domain\Attempt\Guard\AttemptGuard;

class RateAttemptAnswerAction{
    public function __construct(
        protected AttemptGuard $attemptGuard,
        protected FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){}
    public function execute(AttemptAnswer $attemptAnswer, int $mark){
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
        $this->log($attemptAnswer);
    }

    protected function ensureAttemptNotChecked(Attempt $attempt){
        if($attempt->isChecked()){
            throw new AnswerManualCheckException([
                'reason' => 'trying to manual check answer, where attempt is already checked'
            ],'Попытка уже проверена, оценка недоступна');
        }
    }

    protected function ensureTaskIsNotAutoCheck(Task $task){
        if($task->autoCheck()){
            throw new AnswerManualCheckException([
                'reason' => 'trying to manual check answer, where task with auto checking type',
                'task_id' => $task->id
            ],
            'Задание проверяется автоматически');
        }
    }

    protected function ensureMarkIsValid(int $mark, Task $task){
        if($task->mark < $mark){
            throw new AnswerManualCheckException([
                'reason' => 'recieved mark more than max mark',
                'mark' => $mark,
                'max_mark' => $task->mark
            ],'Выставленный балл больше чем максимально возможный');
        }
    }

    protected function ensureAttemptFinished(Attempt $attempt){
        if(!$attempt->isFinished() && !$attempt->isBanned()){
            throw new AnswerManualCheckException([
                'reason' => 'trying to rate answer with not finished attempt',
                'attempt_status' => $attempt->status
            ] ,'Задание возможно оценить только при завершенной попытке');
        }
    }

    protected function log(AttemptAnswer $attemptAnswer){
        LogActivity::event(
            event: Event::Updated,
            resource:Resource::AttemptAnswer,
            context:[
                'attempt_answer_id' => $attemptAnswer->id,
                'status' => 'checked'
            ]);
    }
}