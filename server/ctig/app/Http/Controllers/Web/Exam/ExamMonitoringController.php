<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Action\Monitoring\UpdateProtocolCommentAction;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Http\Resources\Exam\ExamMonitoringResource;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class ExamMonitoringController
{
    public function index(Request $request){
        $user = $request->user();
        $past = $request->boolean('past');
        $exams = Exam::with(['type', 'center'])
            ->whereHas('examiners', function(Builder $query) use($user){
                $query->where('examiner_id', $user->id);
            })
            ->withCount(['enrollments'])
            ->when($past, function (Builder $query){
                $query->whereEndTimeLess(now());
            })
            ->when(!$past, function (Builder $query){
                $query->whereEndTimeMore(now()->subMinutes(30));;
            })
            ->notCancelled()
            ->sorting(Carbon::now())
            ->paginate(10);

        return Inertia::render('ExamMonitoring/ExamMonitoringList', [
            'exams' => ExamIndexResource::collection($exams),
            'past' => $past 
        ]);
    }

    public function show(Exam $exam){
        Gate::authorize('exam-manage-access', $exam);

        $exam->load([
            'enrollments' => ['foreignNational', 'attempt.center'],
            'type'
        ]);
        $exam->enrollments = $exam->enrollments->sortBy('foreignNational.surname');
        
        return Inertia::render('ExamMonitoring/ExamMonitoring', [
            'exam' => new ExamMonitoringResource($exam)
        ]);
    }

    public function updateProtocolComment(Request $request, Exam $exam, UpdateProtocolCommentAction $updateProtocolComment){
        Gate::authorize('exam-manage-access', $exam);
        $request->validate([
            'protocolComment' => ['required', 'string']
        ]);
        $updateProtocolComment->execute($exam, $request->input('protocolComment'));
        
        return response()->noContent();
    }
}
