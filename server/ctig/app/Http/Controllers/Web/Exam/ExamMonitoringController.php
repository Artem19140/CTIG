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
        $employee = $request->user();

        $past = $request->boolean('past');
        
        $exams = Exam::query()
            ->with(['type', 'center'])
            ->visibleFor($employee)
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
        $this->authorize($exam);

        $exam->load([
            'enrollments' => ['foreignNational', 'attempt.center'],
            'type'
        ]);
        $exam->enrollments = $exam->enrollments->sortBy('foreignNational.surname');
        
        return Inertia::render('ExamMonitoring/ExamMonitoring', [
            'exam' => new ExamMonitoringResource($exam),
            'available' => [
                'protocolComment' => $exam->begin_time->isToday() && $exam->begin_time->isPast()
            ]
        ]);
    }

    public function protocolComment(
        Request $request, 
        Exam $exam, 
        UpdateProtocolCommentAction $updateProtocolComment
    ){
        $this->authorize($exam);

        $request->validate([
            'protocolComment' => ['required', 'string']
        ]);

        $updateProtocolComment->execute(
            $exam, 
            $request->input('protocolComment')
        );
        
        return response()->noContent();
    }

    protected function authorize(Exam $exam){
        Gate::authorize('examiner', $exam);
    }
}
