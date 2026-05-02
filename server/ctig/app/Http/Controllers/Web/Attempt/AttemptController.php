<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Domain\Attempt\Action\BanAttemptAction;
use App\Domain\Attempt\Action\FinishAttemptAction;
use App\Domain\Attempt\Action\StartAttemptAction;
use App\Domain\Attempt\Query\GetCurrentAttemptQuery;
use App\Enums\AttemptStatus;
use App\Http\Resources\Attempt\AttemptExamResource;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Exam\ExamShortResource;
use App\Models\Attempt;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

    public function show(
        Attempt $attempt,
        GetCurrentAttemptQuery $getCurrentAttemptQuery
    ){
        $attempt = $getCurrentAttemptQuery->execute($attempt);
        
        return Inertia::render('Attempt/Attempt', [
            'attempt' => new AttemptExamResource($attempt)
        ]);
    }

    public function start(StartAttemptAction $startAttempt, Attempt $attempt)
    {
        if($attempt->status !== AttemptStatus::Pending){
            return redirect('login')->with('У вас нет текущей попытки экзамена');
        }
        $startedAttempt = $startAttempt->execute($attempt);
        return redirect()->route('attempts', ['attempt' => $startedAttempt->id]);
    }

    public function ban(Request $request, Attempt $attempt, BanAttemptAction $banAttempt)
    {
        $request->validate([
            'banReason' => ['required', 'string']
        ]);
        $banAttempt->execute($attempt, $request->input('banReason'), $request->user()->id);
        return response()->noContent();
    }

    public function finish(
        Attempt $attempt, 
        FinishAttemptAction $finishAttempt,
        Request $request
    ){
        $finishAttempt->execute($attempt);
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return Inertia::render('Attempt/AfterAttempt', [
            'foreignNational' => $request->user()->full_name,
            'examName' => $attempt->exam->name
        ]);
    }

    public function before(Attempt $attempt){

        if($attempt->status !== AttemptStatus::Pending){
            return redirect('login')->with('У вас нет текущей попытки экзамена');
        }

        $exam = Exam::with([
            'type'
        ])->find($attempt->exam_id);
        
        return Inertia::render('Attempt/BeforeAttempt', [
            'exam' => new ExamShortResource($exam),
            'duration' => $exam->type->duration,
            'minMark' => $exam->type->min_mark,
            'attempt' => $attempt,
            'tasksCount' => $exam->type->tasks_count,
        ]);
    }
}
