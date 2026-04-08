<?php

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAvailableExamsQuery{
    public function execute(int  $examTypeId, int | null $foreignNationalId):Collection{
        $exams = Exam::select('id', 'begin_time')
                    ->withCount('foreignNationals')
                    ->where('exam_type_id',$examTypeId)
                    ->where('is_cancelled', false)
                    ->where('begin_time_utc', '>', Carbon::now())
                    ->when($foreignNationalId, function (Builder $query) use ($foreignNationalId){
                        $query->whereDoesntHave('foreignNationals', function (Builder $q) use($foreignNationalId){
                            $q->where('foreign_national_id',$foreignNationalId);
                        });
                    })
                    ->whereHas('foreignNationals', function ($q) {
                    }, '<', DB::raw('exams.capacity'))
                    ->orderBy('begin_time') 
                    ->limit(10)
                    ->get();
        return $exams;
    }
}