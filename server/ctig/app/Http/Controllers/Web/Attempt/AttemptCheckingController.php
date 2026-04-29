<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Http\Resources\Attempt\AttemptResource;
use App\Models\Attempt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttemptCheckingController
{
    public function show(Attempt $attempt){
        $attempt->load([
            'taskVariants' => function (BelongsToMany $query){
                $query->whereHas('task', function (Builder $q){
                    $q->whereIn('type', TaskType::manualCheckTypes());
                });
            },
            'taskVariants.task',
            'taskVariants.attemptsAnswer' => function($query)use($attempt){
                $query->where('attempt_id', $attempt->id);
            }
        ]);
        $attempt->taskVariants = $attempt->taskVariants->sortBy('task.order');
        return new AttemptResource($attempt);
    }   

    public function finishChecking(
        Attempt $attempt, 
        FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction
    ){
        if($attempt->hasUncheckedAnswers()){
            throw new BusinessException('Существуют непроверенные задания, завершение невозможно');
        }
        $attempt = $finilizeAttemptCheckingAction->execute($attempt);
        return response()->json([
            'attempt' => new AttemptResource($attempt)
        ]);
    }

    public function finishSpeaking(Attempt $attempt){
        $attempt->loadMissing('exam.type');

        if($attempt->speaking_finished_at){
            throw new BusinessException('Задание на говорение уже пройдено');
        }

        $attempt->speaking_finished_at = Carbon::now();
        $attempt->save();
        return response()->noContent();
    }
}
