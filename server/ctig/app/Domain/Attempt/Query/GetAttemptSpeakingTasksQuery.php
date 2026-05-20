<?php

namespace App\Domain\Attempt\Query;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GetAttemptSpeakingTasksQuery{
    public function execute(Attempt $attempt):Attempt{
        $this->ensureNotBanned($attempt);
        
        $attempt->loadMissing([
            'taskVariants'=> function(BelongsToMany $query) use($attempt){
                $query->whereHas('task', function(Builder $q) {
                    $q->where('type', TaskType::Speaking);
                })->with([
                        'task', 
                        'answers',
                        'attemptsAnswer' => function($query)use($attempt){
                            $query->where('attempt_id', $attempt->id);
                        }
                    ]);
            }
        ]);
        
        $attempt->taskVariants = $attempt->taskVariants->sortBy('task.order');
        return $attempt;
    }

    protected function ensureNotBanned(Attempt $attempt){
        if($attempt->isBanned()){
            throw new BusinessException('Попытка аннулирована');
        }
    }
}