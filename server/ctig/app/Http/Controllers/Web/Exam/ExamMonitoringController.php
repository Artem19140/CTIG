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
            ->whereHas('examiners', function(Builder $query) use($user){
                $query->where('examiner_id', $user->id);
            })
            ->withCount('students')
            ->where('begin_time_utc', '>', now())
            ->where('is_cancelled', false)
            ->orderBy('id')
            ->paginate(10);
        return Inertia::render('ExamMonitoring/ExamMonitoringList', [
            'exams' => ExamResource::collection($exams)
        ]);
    }

    public function show(Request $request, Exam $exam){
        //Только свой тестер и только тестер!
        $user = $request->user();
        
        $canGet = $exam->examiners()->where('examiner_id', $user->id)->first();

        if(!$canGet){
            abort(404);
        }
        if($exam->isCompleted()){
            throw new BusinessException('Экзамен уже прошел');
        }

        $exam->load([
            'examType',
            'students.attempts' => fn ($query) => $query->where('exam_id', $exam->id)
        ]);
        
        return Inertia::render('ExamMonitoring/ExamMonitoring', [
            'students' => fn () => StudentResource::collection($exam->students),
            'exam' => new ExamResource($exam),
            'hasSpeakingTasks' => $exam->examType->has_speaking_tasks,
            'tasksCount' =>   $exam->examType->tasks_count
        ]);
    }
}
