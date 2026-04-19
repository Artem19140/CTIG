<?php

namespace App\Domain\Attempt\Query;

use App\Models\Attempt;
use App\Models\TaskVariant;
use App\Domain\Attempt\Guard\AttemptGuard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetCurrentAttemptQuery{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt):Collection{
        $this->attemptGuard->ensureStarted($attempt);
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotExpired($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        return TaskVariant::with(['answers', 'task', 'attemptsAnswer' => function ($query) use ($attempt) {
                                    $query->where('attempt_id', $attempt->id);
                                }, 'attemptsAnswer.attempt'])
                            ->whereHas('attemptsAnswer', function (Builder $query) use($attempt){
                                $query->where('attempt_id', $attempt->id);
                            })
                            ->get()
                            ->sortBy('task.order')
                            ->values();
    }
}