<?php

namespace App\Actions\Attempt\Tasks;



use App\Enums\TaskType;
use App\Models\Attempt;
use App\Models\TaskVariant;
use App\Validation\AttemptValidation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class GetTasksToCheckAction{
    public function __construct(
        protected AttemptValidation $attemptValidation
    ){}

    public function execute(Attempt $attempt):Collection{
        $this->attemptValidation->ensureNotBanned($attempt);
        $this->attemptValidation->ensureFinished($attempt);
        $tasksToCheck = TaskVariant::whereHas('attemptsAnswers', function(Builder $query)use($attempt){
                            $query->where('attempt_id', $attempt->id)
                                    ->where('is_checked', false);
                        })
                        ->get()
                        ->sortBy('task.order')
                        ->values();
        return $tasksToCheck;
    }
}