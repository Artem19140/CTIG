<?php

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetExamsToCheckQuery{
    public function execute(Employee $employee):Collection{
        return Exam::whereHas('type', function (Builder $query){
                $query->where('need_human_check', true);
            })
            ->whereHas('attempts', function(Builder $query){
                $query->statusUnchecked();
            })
            ->whereHas('examiners', function(Builder $query)use($employee){
                $query->where('examiner_id', $employee->id);
            })
            ->with(['type'])
            ->get();
    }
}