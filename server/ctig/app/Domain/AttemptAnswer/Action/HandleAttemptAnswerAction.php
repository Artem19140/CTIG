<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Domain\AttemptAnswer\Resolvers\TaskHandlerResolver;
use App\Exceptions\Task\TaskAnswersNotAllowedException;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Domain\Attempt\Guard\AttemptGuard;
use App\Models\Task;

class HandleAttemptAnswerAction{
    public function __construct(
        protected AttemptGuard $attemptGuard,
        protected TaskHandlerResolver $taskHandlerResolver
    ){}

    public function execute(
        mixed $answer, 
        Attempt $attempt, 
        AttemptAnswer $attemptAnswer
    ):AttemptAnswer{
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureStarted($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        $this->attemptGuard->ensureNotExpired($attempt);

        $taskVariant = $attemptAnswer->taskVariant;
        $task = $taskVariant->task;

        $this->ensureTaskAllowedAnswers($task, $answer, $attemptAnswer);
        
        $handler = $this->taskHandlerResolver->resolve($task);

        $validatedAnswer = $handler->validate($answer, $taskVariant, $attemptAnswer);

        if($task->type->autoCheck()){
            $mark = $handler->calculateMark($validatedAnswer, $taskVariant);
            $attemptAnswer->mark = $mark;
        }

        $attemptAnswer->answer = $validatedAnswer;
        $attemptAnswer->save();
        return $attemptAnswer;
    }

    protected function ensureTaskAllowedAnswers(
        Task $task, 
        mixed $answer, 
        AttemptAnswer $attemptAnswer
    ):void{
        if(!$task->type->hasAnswers()){
            throw new TaskAnswersNotAllowedException([
                'task_id'=> $task->id,
                'answer' => $answer,
                'attempt_answer_id' => $attemptAnswer->id
            ]);
        }
    }

}