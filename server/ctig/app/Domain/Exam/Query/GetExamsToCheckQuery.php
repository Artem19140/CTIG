<?php

namespace App\Domain\Exam\Query;

use App\Enums\AttemptStatus;
use App\Models\Exam;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetExamsToCheckQuery{
    public function execute(Employee $employee):Collection{
        return Exam::query()
            ->visibleFor($employee)
            ->whereHas('type', function (Builder $query){
                $query->where('need_human_check', true);
            })
            ->whereHas('attempts', function(Builder $query){
                $query
                    ->whereIn('status', [AttemptStatus::Finished, AttemptStatus::Banned])
                    ->whereNull('checked_at');
            })
            ->with(['type'])
            ->get();
    }
}