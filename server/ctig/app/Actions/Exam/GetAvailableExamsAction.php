<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GetAvailableExamsAction{ //По студенту еще ты поиск
    public function execute(int  $examTypeId, int | null $studentId){
        $exams = Exam::select('id', 'begin_time')
                    ->withCount('students')
                    ->where('exam_type_id',$examTypeId)
                    ->where('is_cancelled', false)
                    ->where('begin_time', '>', now())
                    ->when($studentId, function (Builder $query) use ($studentId){
                        $query->whereDoesntHave('students', function (Builder $q) use($studentId){
                            $q->where('student_id',$studentId);
                        });
                    })
                    ->whereHas('students', function ($q) {
                    }, '<', DB::raw('exams.capacity'))
                    ->orderBy('begin_time') 
                    ->limit(10)
                    ->get();
        return $exams;
    }
}