<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Models\Task;

class EssayTaskHandler{
    public function for(Task $task):bool{
        return $task->type === TaskType::Essay;
    }

    public function validate(string $answer){
        return $answer;
    }
}