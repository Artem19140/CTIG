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
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureActive($attempt);
        $this->attemptGuard->ensureNotExpired($attempt);
        return TaskVariant::with(['answers', 'task', 'attemptsAnswers' => function ($query) use ($attempt) {
                                    $query->where('attempt_id', $attempt->id);
                                }])
                            ->whereHas('attemptsAnswers', function (Builder $query) use($attempt){
                                $query->where('attempt_id', $attempt->id);
                            })
                            ->get()
                            ->sortBy('task.order')
                            ->values();
    }
}