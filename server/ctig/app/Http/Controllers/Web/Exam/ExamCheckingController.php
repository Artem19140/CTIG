<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Query\GetExamsToCheckQuery;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamCheckingController
{
    public function index(Request $request, GetExamsToCheckQuery $getExamsToCheckQuery){
        $exams = $getExamsToCheckQuery->execute();
        return Inertia::render('ExamsChecking/ExamsChecking',[
           'exams' => ExamIndexResource::collection($exams) 
        ]);
    }

    public function show(Request $request, Exam $exam){
        $exam->load([
            'enrollments' => function(HasMany $query){
                $query->whereHas('attempt')
                    ->with('attempt')
                    // ->withExists(['answers as has_unchecked_answers' => function(Builder $q){
                    //     $q->whereNull('checked_at');
                    // }]);
                    ;
            },
            'type'
        ]); 
        return Inertia::render('ExamsChecking/ExamChecking', [
            'exam' => new ExamResource($exam)
        ]);
    }
}
