<?php

namespace App\Actions\Attempt;

use App\Models\Attempt;
use App\Models\TaskVariant;
use App\Validation\AttemptValidation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetCurrentAttemptAction{
    public function __construct(
        protected AttemptValidation $attemptValidation
    ){}
    public function execute(Attempt $attempt):Collection{
        $this->attemptValidation->ensureActive($attempt);
        $this->attemptValidation->ensureNotBanned($attempt);
        $this->attemptValidation->ensureNotExpired($attempt);
        return TaskVariant::with(['answers', 'task', 'attemptsAnswers' => function ($query) use ($attempt) {
                                    $query->where('attempt_id', $attempt->id);
                                }])
                            ->whereHas('attemptsAnswers', function (Builder $query) use($attempt){
                                $query->where('attempt_id', $attempt->id);
                            })
                            ->join('tasks', 'task_variants.task_id', '=', 'tasks.id')
                                ->orderBy('tasks.order')
                                ->select('task_variants.*') 
                            ->get();
    }
}