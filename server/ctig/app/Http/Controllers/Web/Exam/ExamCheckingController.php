<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Query\GetExamsToCheckQuery;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamCheckingController
{
    public function index(Request $request, GetExamsToCheckQuery $getExamsToCheckQuery){
        $exams = $getExamsToCheckQuery->execute();
        return Inertia::render('ExamsChecking/ExamsChecking',[
           'exams' => ExamIndexResource::collection($exams) 
        ]);
    }

    public function show(Request $request, Exam $exam){
        $exam->load(['enrollments.attempt' => function (HasOne $query){
                $query
                    ->withExists(['answers as has_unchecked_answers' => function(Builder $q){
                    $q->whereNull('checked_at');
                }]);
        }, 'type']); 
        return Inertia::render('ExamsChecking/ExamChecking', [
            'exam' => new ExamResource($exam)
        ]);
    }
}
