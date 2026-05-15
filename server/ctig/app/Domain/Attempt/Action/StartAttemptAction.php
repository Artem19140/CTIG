<?php

namespace  App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StartAttemptAction{
    public function execute(Attempt $attempt):Attempt{
        
        if($attempt->exam->end_time->isPast()){
            Log::warning('trying to start an exam once it has passed', ['attempt_id' => $attempt->id]);
            throw new BusinessException('Экзамен уже прошел');
        }

        return DB::transaction(function () use($attempt) {
            $attempt->start();
            $attempt->save();
            return $attempt;
        });
        
    }
}