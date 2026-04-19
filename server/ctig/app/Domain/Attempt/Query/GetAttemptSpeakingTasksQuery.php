<?php

namespace App\Domain\Attempt\Query;

use App\Domain\Attempt\Guard\AttemptGuard;
use App\Enums\TaskType;
use App\Models\Attempt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GetAttemptSpeakingTasksQuery{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}

    public function execute(Attempt $attempt):Attempt{
        $this->attemptGuard->ensureNotBanned($attempt);//Как они проводят speaking то??
        $this->attemptGuard->ensureStarted($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        $this->attemptGuard->ensureNotExpired($attempt);
        $attempt->loadMissing([
            'taskVariants'=> function(BelongsToMany $query){
                $query->whereHas('task', function(Builder $q){
                    $q->where('type', TaskType::Speaking);
                });
            },
            'taskVariants.task',
            'taskVariants.answers',
            'taskVariants.attemptsAnswer' => function($query)use($attempt){
                $query->where('attempt_id', $attempt->id);
            }
        ]);
        $attempt->taskVariants = $attempt->taskVariants->sortBy('task.order');
        return $attempt;
    }
}