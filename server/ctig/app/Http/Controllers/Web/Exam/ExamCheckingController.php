<?php

namespace App\Http\Controllers\Web\Exam;

use App\Enums\AttemptStatus;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamCheckingController
{
    public function index(Request $request){
        $now = Carbon::now($request->user()->time_zone);
        $exams = Exam::whereEndTimeLess($now)
                    ->whereHas('attempts', function (Builder $query){
                        $query->where('status', AttemptStatus::Pending);
                    })
                    ->with('type')
            ->get();
        return Inertia::render('ExamsChecking/ExamsChecking',[
           'exams' => ExamIndexResource::collection($exams) 
        ]);
    }

    public function show(Request $request, Exam $exam){
        $exam->load(['enrollments.attempt', 'type']);
        return Inertia::render('ExamsChecking/ExamChecking', [
            'exam' => new ExamResource($exam)
        ]);
    }
}
