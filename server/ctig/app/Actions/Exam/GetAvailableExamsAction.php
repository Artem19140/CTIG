<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;

class GetAvailableExamsAction{ //По студенту еще ты поиск
    public function execute(int  $examTypeId){
        $exams = Exam::select('id', 'begin_time')
                    ->withCount('students')
                    ->where('exam_type_id',$examTypeId)
                    ->where('is_cancelled', false)
                    ->where('begin_time', '>', now())
                
                    // ->whereHas('students',function(Builder $query,){
                        
                    // })
                    ->orderBy('begin_time') 
                    ->limit(10)
                    ->get();
        return $exams;
    }
}