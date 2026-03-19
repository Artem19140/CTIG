<?php

namespace App\Http\Controllers\Web\Exam;

use App\Exceptions\BusinessException;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Student\StudentResource;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamMonitoringController
{
    public function index(Request $request){
        $user = $request->user();
        
        $exams = Exam::with(['examType'])
            ->whereHas('testers', function(Builder $query) use($user){
                $query->where('tester_id', $user->id);
            })
            ->withCount('students')
            ->where('is_cancelled', false)
            ->orderBy('id')
            ->paginate();
        return Inertia::render('ExamMonitoring/ExamMonitoringList', [
            'exams' => ExamResource::collection($exams)
        ]);
    }

    public function show(Request $request, Exam $exam){
        //Только свой тестер и только тестер!
        $user = $request->user();
        
        $canGet = $exam->testers()->where('tester_id', $user->id)->first();

        if(!$canGet){
            abort(404);
        }
        if($exam->isCompleted()){
            throw new BusinessException('Экзамен уже прошел');
        }
        $exam->load([
            'examType',
            'students.attempts' => fn ($query) => $query->where('exam_id', $exam->id)
                                                    ->withCount(['answers' => function ($q){
                                                        $q->where('answer', '<>', null)
                                                        ->orWhere('answer_id', '<>', null);
                                                    }])
        ]);
        return Inertia::render('ExamMonitoring/ExamMonitoring', [
            'students' => fn () => StudentResource::collection($exam->students),
            'exam' => new ExamResource($exam),
            'hasSpeakingTasks' => $exam->examType->has_speaking_tasks,
            'tasksCount' =>   $exam->examType->tasks_count
        ]);
    }
}
