<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Query\GetExamsToCheckQuery;
use App\Http\Resources\Exam\ExamCheckingResource;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;

class ExamCheckingController
{
    public function index(GetExamsToCheckQuery $getExamsToCheckQuery){
        $exams = $getExamsToCheckQuery->execute();
        return Inertia::render('ExamsChecking/ExamsChecking',[
           'exams' => ExamIndexResource::collection($exams) 
        ]);
    }

    public function show(Exam $exam){

        $exam->load([
            'type',
            'enrollments' => function(HasMany $query){
                $query->whereHas('attempt')
                    ->with('attempt.center');
            }
        ]); 

        return Inertia::render('ExamsChecking/ExamChecking', [
            'exam' => new ExamCheckingResource($exam)
        ]);
    }
}
