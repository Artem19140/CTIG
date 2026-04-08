<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Exceptions\BusinessException;
use App\Models\AttemptAnswer;
use App\Models\User;
use DB;
use Server\Ctig\App\Domain\Attempt\Guard\AttemptGuard;

class RateAttemptAnswerAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(AttemptAnswer $attemptAnswer, int $mark, User $user){
        $attempt = $attemptAnswer->attempt;
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureFinished($attempt, 'Оценить можно только завершенную попытку');

        if($attemptAnswer->is_checked){
            throw new BusinessException('Задание уже проверено и оценено');
        }

        $attemptAnswer->load(['taskVariant.task']);

        $task = $attemptAnswer->taskVariant->task;

        if($task->autoCheck()){
            throw new BusinessException('Задание проверяется автоматически');
        }

        if($task->mark < $mark){
            throw new BusinessException('Выставленный балл больше, чем максимально возможный');
        }

        DB::transaction(function () use($attemptAnswer, $attempt, $mark , $user) {
            $attemptAnswer->mark = $mark;
            $attemptAnswer->is_checked = true;
            $attemptAnswer->checked_by_id = $user->id;
            $attemptAnswer->save();
            // $notCheckedAnswersExist = $attempt->answers()
            //                                 ->where('is_checked', false)
            //                                 ->exists();
            // if(!$notCheckedAnswersExist){
            //     $finalizeAttemptChecking->execute($attempt);
            // }
        });
    }
}