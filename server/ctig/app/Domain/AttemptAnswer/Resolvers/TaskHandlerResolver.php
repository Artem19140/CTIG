<?php

namespace App\Domain\AttemptAnswer\Resolvers;

use App\Domain\AttemptAnswer\Handlers\EssayTaskHandler;
use App\Domain\AttemptAnswer\Handlers\MultyInputTaskHandler;
use App\Domain\AttemptAnswer\Handlers\SingleChoiceTaskHandler;
use App\Domain\AttemptAnswer\Handlers\TextInputTaskHandler;
use App\Enums\TaskType;
use App\Exceptions\Task\TaskHandlerNotFoundException;
use App\Models\Task;


class TaskHandlerResolver{

    public function resolve(Task $task)
    {
        return match($task->type){
            TaskType::SingleChoice => new SingleChoiceTaskHandler(),
            TaskType::TextInput => new TextInputTaskHandler(),
            TaskType::Essay => new EssayTaskHandler(),
            TaskType::MultyInput => new MultyInputTaskHandler(),
            default => throw new TaskHandlerNotFoundException([
                'task_id' => $task->id,
                'task_type' => $task->type
            ])
        };
    }
}