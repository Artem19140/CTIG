<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Domain\Attempt\Action\FinilizeAttemptCheckingAction;
use App\Domain\Attempt\Action\ZeroEmptyAutoCheckAnswersAction;
use App\Domain\Attempt\Guard\AttemptGuard;
use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Http\Resources\Attempt\AttemptResource;
use App\Models\Attempt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class AttemptCheckingController
{
    public function show(Request $request, Attempt $attempt){
        $attempt->load([
            'taskVariants' => function (BelongsToMany $query){
                $query->whereHas('task', function (Builder $q){
                    $q->whereIn('type', TaskType::manualCheckTypes());
                });
            },
            'taskVariants.task',
            'taskVariants.attemptsAnswer' => function($query)use($attempt){
                $query->where('attempt_id', $attempt->id);
            },
            'taskVariants.attemptsAnswer.attempt'//Убрать  attempt и вообще эту строку
        ]);
        $attempt->taskVariants = $attempt->taskVariants->sortBy('task.order');
        return new AttemptResource($attempt);
    }   

    public function finishChecking(
                                    Request $request, 
                                    Attempt $attempt, 
                                    FinilizeAttemptCheckingAction $finilizeAttemptCheckingAction,
                                    ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswers
                                ){
        
        $zeroEmptyAutoAnswers->execute($attempt);
        if($attempt->hasUncheckedAnswers()){
            throw new BusinessException('Существуют непроверенные задания, завершение невозможно');
        }
        $attempt = $finilizeAttemptCheckingAction->execute($attempt);
        return response()->json([
            'attempt' => new AttemptResource($attempt)
        ]);
    }
}
