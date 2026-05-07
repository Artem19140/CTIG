<?php

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetExamsToCheckQuery{
    public function execute(User $user):Collection{
        return Exam::whereHas('type', function (Builder $query){
                $query->where('need_human_check', true);
            })
            ->whereHas('attempts', function(Builder $query){
                $query->statusUnchecked();
            })
            ->whereHas('examiners', function(Builder $query)use($user){
                $query->where('examiner_id', $user->id);
            })
            ->with(['type'])
            ->get();
    }
}