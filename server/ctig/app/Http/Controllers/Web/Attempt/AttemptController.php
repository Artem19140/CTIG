<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Actions\Attempt\FinalizeAttemptCheckingAction;
use App\Actions\Attempt\StartAttemptAction;
use App\Enums\AttemptStatus;
use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Attempt;
use App\Models\TaskVariant;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use ZeroEmptyAutoCheckAnswersAction;

class AttemptController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->input('studentId');
        $examId = $request->input('examId');
        $examAttempts = Attempt::when($studentId, function (Builder $query, int $studentId){
                $query->where('student_id', $studentId);
            })
            ->when($examId, function (Builder $query, int $examId){
                $query->where('exam_id', $examId);
            })
            ->get();
        return AttemptResource::collection($examAttempts);
    }

    public function current(Request $request){
        $student = $request->user();
        $currentAttempt = $student->attempts()->where('status', AttemptStatus::Active)->first();
        if(!$currentAttempt){
            return redirect('login')->with('У вас нет текущей попытки экзамена');
        }
        if($currentAttempt->isExpired() || !$currentAttempt->isActive()){
            $currentAttempt->finish();
            $currentAttempt->save();
            return redirect('login')->with('Попытка неактивна');
        }
        // $attemptTasks = TaskVariant::with(['answers', 'task', 'studentsAnswers'])
        //                                 ->whereHas('studentsAnswers', function (Builder $query) use($currentAttempt){
        //                                     $query->where('attempt_id', $currentAttempt->id);
        //                                 })->get();
        $attemptTasks = TaskVariant::with([
            'answers',
            'task',
            'studentsAnswers' => function ($query) use ($currentAttempt) {
                $query->where('attempt_id', $currentAttempt->id);
            }
        ])->get();

        // foreach($attemptTasks as $e){
        //     echo $e;
        // }die;
        return Inertia::render('Attempt/Attempt', [
            'attempt' => new AttemptResource($currentAttempt),
            'tasks'=> TaskVariantResource::collection($attemptTasks)
        ]);
    }

    public function start(Request $request, StartAttemptAction $startAttempt)
    {
        $student = $request->user();
        $pendingAttempt = $student->attempts()->where('status', AttemptStatus::Pending)->first();
        if(!$pendingAttempt){
            return redirect('login')->with('У вас нет попытки экзамена');
        }
        DB::transaction(function() use($pendingAttempt, $student, $startAttempt){
            $startAttempt->execute($pendingAttempt, $student);
        });
        return redirect('exam-attempts');
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
        $attempt->status = AttemptStatus::Banned;
        $attempt->save();
        return $this->noContent();
    }

    public function speaking(Attempt $attempt){
        if($attempt->isBanned()){
            throw new BusinessException('Попытка заблокирована');
        }
        
        if($attempt->isExpired() || !$attempt->isActive()){
            throw new BusinessException('Попытка неактивна');
        }

        $studentAnswer =  $attempt->answers()->with('taskVariant')
                                        ->whereHas('taskVariant.task', function(Builder $query){
                                            $query->where('type', TaskType::Speaking);
                                        })
                                        ->get();
        $taskVariants=$studentAnswer->pluck('taskVariant');
        return TaskVariantResource::collection($taskVariants);
    }

    public function finish(
                                Attempt $attempt, 
                                FinalizeAttemptCheckingAction $finalizeAttemptChecking,
                                ZeroEmptyAutoCheckAnswersAction $zeroEmptyAutoAnswers
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
            $attempt->lockForUpdate()->finish();
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
            //request()->user()->tokens()->delete();
        });
        return $this->noContent();
    }

    public function answersToCheck(Attempt $attempt){
        $uncheckedAnswers =  $attempt->answers()
                                    ->where('is_checked', false)
                                    ->whereHas('attempt', function(Builder $query){
                                        $query->where('status', AttemptStatus::Finished);
                                    })
                                    ->whereHas('taskVariant.task', function(Builder $query){
                                        $query->whereIn('type', TaskType::manualCheckTypes());
                                    })
                                    ->get();
        return $this->ok(StudentAnswerResource::collection($uncheckedAnswers));
    }

    public function toCheck(){
        $unCheckedAttempts = Attempt::where('status', AttemptStatus::Finished)->get();
        return AttemptResource::collection($unCheckedAttempts);
    }

    public function full(Attempt $attempt){
        $full = $attempt->load(['answers.taskVariant.answers', 'student']);
        return $this->ok( new AttemptResource($full)); 
    }
}
