<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Enums\TaskType;
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
                })
                ->whereHas('attemptsAnswer', function (Builder $q){
                    $q->notChecked();
                })
                ;
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
}
