<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Actions\Attempt\Checking\FinalizeAttemptCheckingAction;
use App\Actions\Attempt\GetCurrentAttemptAction;
use App\Actions\Attempt\StartAttemptAction;
use App\Enums\AttemptStatus;
use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\attemptAnswer\AttemptAnswerResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\TaskVariant;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use App\Actions\Attempt\ZeroEmptyAutoCheckAnswersAction;

class AttemptController extends Controller
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
                            GetCurrentAttemptAction $getCurrentAttempt
                        ){
        $attemptTasks = $getCurrentAttempt->execute($attempt);
        return Inertia::render('Attempt/Attempt', [
            'attempt' => new AttemptResource($attempt),
            'tasks'=> TaskVariantResource::collection($attemptTasks)
        ]);
    }

    public function start(Request $request, StartAttemptAction $startAttempt, Attempt $attempt)
    {
        $foreignNational = $request->user();
        if($attempt->status != AttemptStatus::Pending){
            abort(403);
        }
        
        $startedAttempt = DB::transaction(function() use($attempt, $foreignNational, $startAttempt){
            return $startAttempt->execute($attempt, $foreignNational);
        });
        
        return redirect()->route('exam-attempts', ['attempt' => $startedAttempt->id]);
    }

    public function show(Attempt $attempt)
    {
        $attempt->load('answers');
        return new AttemptResource($attempt);
    }

    public function ban(Request $request, Attempt $attempt)
    {
        $request->validate([
            'banReason' => ['required', 'string']
        ]);
        
        if($attempt->isBanned()){
            throw new BusinessException('Попытка уже аннулирована');
        }

        $attempt->ban_reason = $request->input('banReason');
        $attempt->ban_by_id = $request->user()->id;
        $attempt->is_passed = false;
        $attempt->finished_at = Carbon::now();
        $attempt->status = AttemptStatus::Banned;
        $attempt->save();
        return $this->noContent();
    }

    public function speaking(Attempt $attempt){
        $exam = Exam::findOrFail($attempt->exam_id);
        Gate::authorize('exam-manage-access', $exam);
        if($attempt->isBanned()){
            throw new BusinessException('Попытка заблокирована');
        }
        
        if($attempt->isExpired() || !$attempt->isActive()){
            throw new BusinessException('Попытка неактивна');
        }

        $attemptAnswer =  $attempt->answers()->with('taskVariant')
                                        ->whereHas('taskVariant.task', function(Builder $query){
                                            $query->where('type', TaskType::Speaking);
                                        })
                                        ->get();
        $taskVariants=$attemptAnswer->pluck('taskVariant');
        return TaskVariantResource::collection($taskVariants);
    }

    public function finish(
                                Attempt $attempt, 
                                FinalizeAttemptCheckingAction $finalizeAttemptChecking,
                                ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswers,
                                Request $request
                           )
    {
        if(!$attempt->isActive()){
            throw new BusinessException('Попытка уже завершена или аннулирована');
        }

        DB::transaction(function() use(
                                            $attempt,
                                            $finalizeAttemptChecking,
                                            $zeroEmptyAutoAnswers
                                        ){
            $attempt->lockForUpdate(); //Не работает
            $attempt->finish();
            $zeroEmptyAutoAnswers->execute($attempt);

            $hasUncheckedManualAnswers = $attempt->answers()
                                            ->where('is_checked', false)
                                            ->whereHas('taskVariant.task', function(Builder $query){
                                                $query->whereIn('type', TaskType::manualCheckTypes());
                                            })
                                            ->exists();
            if(!$hasUncheckedManualAnswers){
               $finalizeAttemptChecking->execute($attempt);
            }
            $attempt->save();
        });
        $exam = Exam::with('examType')->find($attempt->exam_id);
        return Inertia::render('Attempt/AfterAttempt', [
            'foreignNational' => $request->user(),
            'examName' => $exam->examType->name
        ]);
    }

    public function tasksToCheck(Attempt $attempt){
        $exam = Exam::findOrFail($attempt->exam_id);
        Gate::authorize('exam-manage-access', $exam);
        $uncheckedAnswers =  $attempt->answers()
                                    ->with(['taskVariant.task'])
                                    ->where('is_checked', false)
                                    ->whereHas('attempt', function(Builder $query){
                                        $query->where('status', AttemptStatus::Finished);
                                    })
                                    ->whereHas('taskVariant.task', function(Builder $query){
                                        $query->whereIn('type', TaskType::manualCheckTypes());
                                    })
                                    ->get();
        return AttemptAnswerResource::collection($uncheckedAnswers);
    }

    public function toCheck(){
        $unCheckedAttempts = Attempt::with('exam.examType')
                                ->where('status', AttemptStatus::Finished)->paginate();

        return Inertia::render('AttemptsChecking/AttemptsChecking',[
           'attempts' => AttemptResource::collection($unCheckedAttempts) 
        ]);
    }

    public function before(Request $request, Attempt $attempt){
        $foreignNational = $request->user();

        if($attempt->status !== AttemptStatus::Pending){
            return redirect('login')->with('У вас нет текущей попытки экзамена');
        }
        $exam = Exam::with([
            'examType'
        ])->find($foreignNational->exam_id);
        
        return Inertia::render('Attempt/BeforeAttempt', [
            'exam' => new ExamResource($exam),
            'duration' => $exam->examType->duration,
            'minMark' => $exam->examType->min_mark,
            'attempt' => $attempt,
            'tasksCount' => $exam->examType->tasks_count,
        ]);
    }
}
