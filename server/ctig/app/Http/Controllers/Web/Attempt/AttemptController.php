<?php

namespace App\Http\Controllers\Web\Attempt;


use App\Domain\Attempt\Action\BanAttemptAction;
use App\Domain\Attempt\Action\FinishAttemptAction;
use App\Domain\Attempt\Action\StartAttemptAction;
use App\Domain\Attempt\Query\GetCurrentAttemptQuery;
use App\Domain\Attempt\Query\GetSpeakingTasksQuery;
use App\Enums\AttemptStatus;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Attempt;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AttemptController
{
    public function index(Request $request)
    {
        $foreignNationalId = $request->input('foreignNationalId');
        $examId = $request->input('examId');
        $examAttempts = Attempt::when($foreignNationalId, function (Builder $query, int $foreignNationalId){
                $query->where('foreign_national_id', $foreignNationalId);
            })
            ->when($examId, function (Builder $query, int $examId){
                $query->where('exam_id', $examId);
            })
            ->get();
        return AttemptResource::collection($examAttempts);
    }

    public function current(
                            Attempt $attempt,
                            GetCurrentAttemptQuery $getCurrentAttemptQuery
                        ){
        $attemptTasks = $getCurrentAttemptQuery->execute($attempt);
        return Inertia::render('Attempt/Attempt', [
            'attempt' => new AttemptResource($attempt),
            'tasks'=> TaskVariantResource::collection($attemptTasks)
        ]);
    }

    public function start(Request $request, StartAttemptAction $startAttempt, Attempt $attempt)
    {
        $startedAttempt = $startAttempt->execute($attempt);
        return redirect()->route('exam-attempts', ['attempt' => $startedAttempt->id]);
    }

    public function ban(Request $request, Attempt $attempt, BanAttemptAction $banAttempt)
    {
        $request->validate([
            'banReason' => ['required', 'string']
        ]);
        $banAttempt->execute($attempt, $request->input('banReason'), $request->user()->id);
        return back();
    }

    public function speaking(Attempt $attempt, GetSpeakingTasksQuery $getSpeakingQuery){
        $exam = Exam::findOrFail($attempt->exam_id);
        Gate::authorize('exam-manage-access', $exam);
        $speakingTasks = $getSpeakingQuery->execute($attempt);
        return TaskVariantResource::collection($speakingTasks);
    }

    public function finish(
                                Attempt $attempt, 
                                FinishAttemptAction $finishAttempt,
                                Request $request
                           )
    {
        $finishAttempt->execute($attempt);
        return Inertia::render('Attempt/AfterAttempt', [
            'foreignNational' => $request->user(),
            'examName' => $attempt->exam->examType->name
        ]);
    }

    // public function tasksToCheck(Attempt $attempt, GetTasksToCheck $getTasksToCheck){
    //     $exam = Exam::findOrFail($attempt->exam_id);
    //     Gate::authorize('exam-manage-access', $exam);
    //     $tasksToCheck = $getTasksToCheck->execute($attempt);
    //     return TaskVariantResource::collection($tasksToCheck);
    // }

    public function toCheck(){
        $unCheckedAttempts = Attempt::with('exam.examType')
                                ->where('status', AttemptStatus::Finished)->paginate();

        return Inertia::render('AttemptsChecking/AttemptsChecking',[
           'attempts' => AttemptResource::collection($unCheckedAttempts) 
        ]);
    }

    public function before(Request $request, Attempt $attempt){

        if($attempt->status !== AttemptStatus::Pending){
            return redirect('login')->with('У вас нет текущей попытки экзамена');
        }

        $exam = Exam::with([
            'examType'
        ])->find($attempt->exam_id);
        
        return Inertia::render('Attempt/BeforeAttempt', [
            'exam' => new ExamResource($exam),
            'duration' => $exam->examType->duration,
            'minMark' => $exam->examType->min_mark,
            'attempt' => $attempt,
            'tasksCount' => $exam->examType->tasks_count,
        ]);
    }
}
