<?php

namespace App\Domain\Exam\Query;

use App\Enums\AttemptStatus;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetExamsToCheckQuery{
    public function execute():Collection{
        return Exam::whereHas('type', function (Builder $query){
                $query->where('need_human_check', true);
            })
            ->whereHas('attempts', function(Builder $query){
                $query->whereIn('status', AttemptStatus::unChecked())
                ->withExists(['answers' => function(Builder $q){
                    $q->where('checked_at', null);
                }]);
            })
            ->with(['type'])
            ->get();
    }
}