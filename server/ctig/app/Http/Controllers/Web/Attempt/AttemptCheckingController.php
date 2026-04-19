<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Enums\TaskType;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Attempt;
use App\Models\TaskVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttemptCheckingController
{
    public function show(Request $request, Attempt $attempt){
        // $attempt->load(['answers' => function(Builder $query){
        //     $query->where('is_checked', false)
        //             ->whereHas('taskVariant.task', function(Builder $q){
        //                 $q->where('type', TaskType::manualCheckTypes());
        //             });
        // }]
        // );
        $tasks = TaskVariant::with(['answers', 'task', 'attemptsAnswer' => function (HasOne $query) use ($attempt) {
                                    $query->where('attempt_id', $attempt->id)
                                        ->where('is_checked', false);
                                        // ->whereHas('taskVariant.task', function(Builder $q){
                                        //     $q->where('type', TaskType::manualCheckTypes());
                                        // });
                                }])
                            ->whereHas('attemptsAnswer', function (Builder $query) use($attempt){
                                $query->where('attempt_id', $attempt->id);
                            })
                            ->limit(5)
                            ->get()
                            ->sortBy('task.order')
                            ->values();
        return  TaskVariantResource::collection($tasks);
        return new AttemptResource($attempt);
    }   
}
