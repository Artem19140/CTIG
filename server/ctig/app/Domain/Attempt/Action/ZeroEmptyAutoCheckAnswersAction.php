<?php

namespace App\Domain\Attempt\Action;

use App\Enums\TaskType;
use App\Models\Attempt;
use Illuminate\Database\Eloquent\Builder;

class ZeroEmptyAutoCheckAnswersAction{
    public function execute(Attempt $attempt){
        $emptyAnswers =  $attempt->answers()
                                    ->where('is_checked', false)
                                    ->whereHas('taskVariant.task', function(Builder $query){
                                        $query->whereIn('type', TaskType::autoCheckTypes());
                                    })
                                    ->get();
        if($emptyAnswers->isNotEmpty()){
            $emptyAnswers->each(function ($answer){
                $answer->is_checked = true;
                $answer->mark = 0;
                $answer->save();
            });
        }
    }
}