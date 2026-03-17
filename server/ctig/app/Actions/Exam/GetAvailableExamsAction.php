<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;

class GetAvailableExamsAction{ //По студенту еще ты поиск
    public function execute(int | null $examTypeId){
        return Exam::with(['examType', 'address'])
                    ->when($examTypeId, function (Builder $query,$examTypeId) {
                        $query->where('exam_type_id', $examTypeId);
                    })
                    ->where('is_cancelled', false)
                    ->where('begin_time', '>', now())
                    ->orderBy('begin_time') 
                    ->limit(10)
                    ->get();
    }
}