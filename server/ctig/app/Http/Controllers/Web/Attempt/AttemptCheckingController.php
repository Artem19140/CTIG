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
        // $tasks = TaskVariant::with(['answers', 'task', 'attemptsAnswer' => function (HasOne $query) use ($attempt) {
        //                             $query->where('attempt_id', $attempt->id)
        //                                 ->notChecked()
        //                                 ->whereHas('taskVariant.task', function(Builder $q){
        //                                     $q->whereIn('type', TaskType::manualCheckTypes());
        //                                 });
        //                         }])
        //                     ->whereHas('attemptsAnswer', function (Builder $query) use($attempt){
        //                         $query->where('attempt_id', $attempt->id);
        //                     })
        //                     ->whereHas('task', function(Builder $q){
        //                         $q->whereIn('type', TaskType::manualCheckTypes());
        //                     })
        //                     ->get()
        //                     ->sortBy('task.order')
        //                     ->values();
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
            'taskVariants.task'
        ]);
        $attempt->taskVariants->sordBy('task.order');
        return new AttemptResource($attempt);
    }   
}
