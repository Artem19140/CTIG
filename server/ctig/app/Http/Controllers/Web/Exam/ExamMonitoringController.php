<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Action\Monitoring\UpdateProtocolCommentAction;
use App\Http\Resources\Exam\ExamResource;
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
        $exams = Exam::with(['type'])
            ->whereHas('examiners', function(Builder $query) use($user){
                $query->where('examiner_id', $user->id);
            })
            ->withCount(['enrollments'])
            ->with(['examiners', 'address']) //Убрать
            ->when($past, function (Builder $query) use($request){
                $query->where('end_time', '<', now($request->user()->center->time_zone));
            })
            ->when(!$past, function (Builder $query) use($request){
                $query->where('end_time', '>', now($request->user()->center->time_zone)->subMinutes(30));
            })
            ->notCancelled()
            ->sorting(Carbon::now($user->time_zone))
            ->paginate(10);

        return Inertia::render('ExamMonitoring/ExamMonitoringList', [
            'exams' => ExamResource::collection($exams),
            'past' => $past 
        ]);
    }

    public function show(Exam $exam){
        Gate::authorize('exam-manage-access', $exam);

        $exam->load([
            'enrollments' => ['foreignNational', 'attempt'],
            'type'
        ]);
        $exam->enrollments = $exam->enrollments->sortBy('foreignNational.surname');
        
        return Inertia::render('ExamMonitoring/ExamMonitoring', [
            'exam' => new ExamResource($exam)
        ]);
    }

    public function updateProtocolComment(Request $request, Exam $exam, UpdateProtocolCommentAction $updateProtocolComment){
        Gate::authorize('exam-manage-access', $exam);
        $request->validate([
            'protocolComment' => ['required', 'string']
        ]);
        $updateProtocolComment->execute($exam, $request->input('protocolComment'));
        Inertia::flash('success', 'Комментарий добавлен');
        return back();
    }
}
