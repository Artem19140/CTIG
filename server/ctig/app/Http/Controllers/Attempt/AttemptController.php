<?php

namespace App\Http\Controllers\Attempt;

use App\Actions\Attempt\FormAttemptExamVariantAction;
use App\Actions\Attempt\GetDetailedAttemptResultsAction;
use App\Actions\Exam\CheckPassingThresholdAction;
use App\Enums\AttemptStatus;
use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Exam;
use App\Models\Attempt;
use App\Models\StudentAnswer;
use App\Models\TaskVariant;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Enums\TokenAbilities;

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

    public function store(Request $request, FormAttemptExamVariantAction $formAttemptExamVariant)
    {
        $student=$request->user();
        $hasCurrentExamAttempt = $student->attempts()
                                    ->where('status',  AttemptStatus::Active)
                                    ->exists();
                                    
        // if($hasCurrentExamAttempt){
        //     throw new BusinessException('Существует текущая попытка экзамена');
        // }
        
        $exam = Exam::with('examType.blocks.subblocks.tasks.variants') // мб урбать answers
                    ->find($student->exam_id);
        $examAttempt = DB::transaction(function () use($student, $exam, $formAttemptExamVariant) {
            $examDuration = $exam->examType->duration;
            $attempt = Attempt::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'expired_at' => Carbon::now()->addMinutes($examDuration),
                    'started_at' => Carbon::now()
                ]);
            
            $tasks = $exam->examType->blocks
                ->pluck('subblocks')
                ->flatten()
                ->pluck('tasks')
                ->flatten(); 

            $examVariant = $formAttemptExamVariant->execute($tasks, $exam, $attempt, $student);
            
            StudentAnswer::insert($examVariant);
            //$student->tokens()->delete();
            $student->token = $student->createToken(
                TokenAbilities::ExamAccess->value,
                [TokenAbilities::ExamAccess->value],
                Carbon::now()->addMinutes($examDuration + 1)
            )->plainTextToken;
            $attempt->load(['answers.taskVariant.answers', 'student']);
            return $attempt;
        });
        return $this
            ->created(new AttemptResource($examAttempt)
                ->additional(['token' => $student->token])
            );
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
        $attempt->status = AttemptStatus::Banned;
        $attempt->save();
    }

    public function speaking(Attempt $attempt){
        if($attempt->isBanned()){
            throw new BusinessException('Попытка заблокирована');
        }
        
        if($attempt->isExpired() || !$attempt->isActive()){
            $attempt->finish();
            $attempt->save();
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

    public function finish(Attempt $attempt, CheckPassingThresholdAction $checkPassingThreshold)
    {
        if(!$attempt->status->canBeFinished()){
            throw new BusinessException('Попытка уже завершена или аннулирована');
        }

        DB::transaction(function() use($attempt, $checkPassingThreshold){
            $attempt->finish();
            //request()->user()->tokens()->delete();
            $emptyAnswers =  $attempt->answers()
                                    ->where('is_checked', false)
                                    ->whereHas('taskVariant.task', function(Builder $query){
                                        $query->whereIn('type', TaskType::autoCheckTypes());
                                    })
                                    ->get();
            if($emptyAnswers->isNotEmpty()){
                $emptyAnswers->each(function ($answer){
                    $answer->is_checked = true;
                    $answer->mark = 0;
                    $answer->save();
                });
            }

            $hasUncheckedManualAnswers = $attempt->answers()
                                                ->where('is_checked', false)
                                                ->whereHas('taskVariant.task', function(Builder $query){
                                                    $query->whereIn('type', TaskType::manualCheckTypes());
                                                })
                                                ->exists();
            $checkPassingThreshold->execute($attempt);
            if(!$hasUncheckedManualAnswers){
                //вынести в action, потом мокать
                //$attempt->checked();
                $markCount = $attempt->answers()->sum('mark');
                $hasPassed = $checkPassingThreshold->execute($attempt);
                //echo GetDetailedAttemptResultsAction::execute($attempt);
                if($hasPassed){
                    $attempt->is_passed = true;
                }else{
                    $attempt->is_passed = false;
                }
                $attempt->total_mark = $markCount;
            }
            $attempt->save();
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
        $uncheckedAttempts = Attempt::where('status', AttemptStatus::Finished)->get();
        return AttemptResource::collection($uncheckedAttempts);
    }

    public function full(Attempt $attempt){
        //$attempt->answers()->with('taskVariant')->get();
        $full = $attempt->load(['answers.taskVariant.answers', 'student']);
        return $this->ok( new AttemptResource($full)); 
    }
}
