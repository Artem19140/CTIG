<?php

namespace App\Http\Controllers\Web\Exam;

use App\Exceptions\BusinessException;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\ForeignNational\ForeignNationalResource;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class ExamMonitoringController
{
    public function index(Request $request){
        $user = $request->user();
        
        $exams = Exam::with(['examType'])
            ->whereHas('examiners', function(Builder $query) use($user){
                $query->where('examiner_id', $user->id);
            })
            ->withCount('foreignNationals')
            ->where('end_time', '>', now($request->user()->organization->time_zone))
            ->where('is_cancelled', false)
            ->orderBy('id')
            ->paginate(10);
        return Inertia::render('ExamMonitoring/ExamMonitoringList', [
            'exams' => ExamResource::collection($exams)
        ]);
    }

    public function show(Exam $exam){
        Gate::authorize('exam-manage-access', $exam);

        if($exam->isCompleted()){
            throw new BusinessException('Экзамен уже прошел');
        }

        $exam->load([
            'foreignNationals.attempts' => fn ($query) => $query->where('exam_id', $exam->id)
        ]);
        
        return Inertia::render('ExamMonitoring/ExamMonitoring', [
            'foreignNationals' => fn () => ForeignNationalResource::collection($exam->foreignNationals),
            'exam' => new ExamResource($exam),
            'hasSpeakingTasks' => $exam->examType->has_speaking_tasks,
        ]);
    }
}
