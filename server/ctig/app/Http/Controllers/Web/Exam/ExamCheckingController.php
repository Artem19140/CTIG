<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Query\GetExamsToCheckQuery;
use App\Http\Resources\Exam\ExamCheckingResource;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExamCheckingController
{
    public function index(Request $request, GetExamsToCheckQuery $getExamsToCheckQuery){
        $exams = $getExamsToCheckQuery->execute($request->user());
        return Inertia::render('ExamsChecking/ExamsChecking',[
           'exams' => ExamIndexResource::collection($exams) 
        ]);
    }

    public function show(Exam $exam){
        Gate::authorize('exam-examiner-access', $exam);
        
        if(!$exam->type->need_human_check){
            abort(403);
        }
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
