<?php

namespace App\Actions\Attempt;

use App\Models\Attempt;
use App\Models\TaskVariant;
use Illuminate\Database\Eloquent\Builder;
use App\Actions\Attempt\EnsureAttemptIsActiveAction;
use App\Actions\Attempt\EnsureAttemptIsNotBannedAction;
use Illuminate\Database\Eloquent\Collection;

class GetCurrentAttemptAction{
    public function __construct(
        protected EnsureAttemptIsActiveAction $ensureAttemptIsActive,
        protected EnsureAttemptIsNotBannedAction $ensureAttemptIsNotBanned
    ){}
    public function execute(Attempt $attempt):Collection{
        $this->ensureAttemptIsActive->execute($attempt);
        $this->ensureAttemptIsNotBanned->execute($attempt);
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