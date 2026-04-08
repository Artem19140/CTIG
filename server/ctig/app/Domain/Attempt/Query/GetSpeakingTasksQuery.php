<?php

namespace App\Domain\Attempt\Query;



use App\Enums\TaskType;
use App\Models\Attempt;
use App\Models\TaskVariant;
use App\Validation\AttemptValidation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class GetSpeakingTasksQuery{
    public function __construct(
        protected AttemptValidation $attemptValidation
    ){}

    public function execute(Attempt $attempt):Collection{
        $this->attemptValidation->ensureNotBanned($attempt);//Как они проводят speaking то??
        $this->attemptValidation->ensureActive($attempt);
        $speakingTasks = TaskVariant::whereHas('attemptsAnswers', function(Builder $query)use($attempt){
                            $query->where('attempt_id', $attempt->id);
                        })
                        ->whereHas('task', function(Builder $query){
                            $query->where('type', TaskType::Speaking);
                        })
                        ->get()
                        ->sortBy('task.order')
                        ->values();
        return $speakingTasks;
    }
}