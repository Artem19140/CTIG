<?php

namespace App\Domain\AttemptAnswer\Resolvers;

use App\Domain\AttemptAnswer\Handlers\EssayTaskHandler;
use App\Domain\AttemptAnswer\Handlers\SingleChoiceTaskHandler;
use App\Domain\AttemptAnswer\Handlers\TextInputTaskHandler;
use App\Exceptions\Task\TaskHandlerNotFoundException;
use App\Models\Task;


class TaskHandlerResolver{
    protected array $handlers;

    public function __construct()
    {
        $this->handlers = [
            new SingleChoiceTaskHandler(),
            new TextInputTaskHandler(),
            new EssayTaskHandler()
        ];
    }

    public function resolve(Task $task)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->for($task)) {
                return $handler;
            }
        }
        throw new TaskHandlerNotFoundException([
            'task_id' => $task->id,
            'task_type' => $task->type
        ]);
    }
}