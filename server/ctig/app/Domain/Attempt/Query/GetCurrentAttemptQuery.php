<?php

namespace App\Domain\Attempt\Query;

use App\Enums\TaskType;
use App\Models\Attempt;
use App\Models\TaskVariant;
use App\Domain\Attempt\Guard\AttemptGuard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GetCurrentAttemptQuery{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt){
        $this->attemptGuard->ensureStarted($attempt);
        $this->attemptGuard->ensureNotBanned($attempt);
       //$this->attemptGuard->ensureNotExpired($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        $attempt->load([
            'taskVariants'=> function(BelongsToMany $query){
                $query->whereHas('task', function(Builder $q){
                    $q->where('type', '<>', TaskType::Speaking);
                });
            },
            'taskVariants.task',
            'taskVariants.answers',
            'taskVariants.attemptsAnswer' => function($query)use($attempt){
                $query->where('attempt_id', $attempt->id);
            }
        ]);
        //$attempt->taskVariants = $attempt->taskVariants->sordBy('task.order');
        return $attempt;

    }
}