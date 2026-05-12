<?php

namespace  App\Domain\Attempt\Action;

use App\Models\Attempt;

use Illuminate\Support\Facades\DB;
use App\Domain\Attempt\Guard\AttemptGuard;

class StartAttemptAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}
    public function execute(Attempt $attempt):Attempt{
        $this->attemptGuard->ensureNotBanned($attempt);

        return DB::transaction(function () use($attempt) {
            $attempt->start();
            $attempt->save();
            return $attempt;
        });
        
    }
}