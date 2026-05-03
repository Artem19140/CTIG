<?php

namespace App\Domain\Exam\Query;

use App\Models\Enrollment;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAvailableExamsQuery{
    public function execute(int  $examTypeId, int | null $foreignNationalId):Collection{
        $enrollmentCloseBeforeMinutes = Enrollment::CLOSE_BEFORE_START_MINUTES;
        $exams = Exam::select('id', 'begin_time', 'center_id')
            ->withCount('enrollments')
            ->with(['center'])
            ->where('exam_type_id',$examTypeId)
            ->notCancelled()
            ->whereBeginTimeMore(Carbon::now()->addMinutes($enrollmentCloseBeforeMinutes))
            ->when($foreignNationalId, function (Builder $query) use ($foreignNationalId){
                $query->whereDoesntHave('enrollments', function (Builder $q) use($foreignNationalId){
                    $q->where('foreign_national_id',$foreignNationalId);
                });
            })
            ->whereHas('enrollments', function (Builder $q) {
            }, '<', DB::raw('exams.capacity'))
            ->orderBy('begin_time') 
            ->limit(10)
            ->get();
        return $exams;
    }
}