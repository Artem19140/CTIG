<?php

namespace App\Http\Controllers\Api\Attempt;

use App\Actions\Attempt\Checking\FinalizeAttemptCheckingAction;
use App\Actions\Attempt\GenerateExamVariantAction;
use App\Enums\AttemptStatus;
use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\AttemptAnswer\AttemptAnswersResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Exam;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Enums\TokenAbilities;
use ZeroEmptyAutoCheckAnswersAction;

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

    public function store(Request $request, GenerateExamVariantAction $generateExamVariant)
    {
        $foreignNational=$request->user();
        $hasCurrentAttempt = $foreignNational->attempts()
                                    ->where('status',  AttemptStatus::Active)
                                    ->exists();
                                    
        if($hasCurrentAttempt){
            throw new BusinessException('Существует текущая попытка экзамена');
        }
        
        $exam = Exam::with('examType.blocks.subblocks.tasks.variants')
                    ->find($foreignNational->exam_id);
                    
        $examAttempt = DB::transaction(function () use($foreignNational, $exam, $generateExamVariant) {
            $examDuration = $exam->examType->duration;
            $attempt = Attempt::create([
                    'foreign_national_id' => $foreignNational->id,
                    'exam_id' => $exam->id,
                    'expired_at' => Carbon::now()->addMinutes($examDuration),
                    'started_at' => Carbon::now()
                ]);
            
            $tasks = $exam->examType->blocks
                ->pluck('subblocks')
                ->flatten()
                ->pluck('tasks')
                ->flatten(); 

            $examVariant = $generateExamVariant->execute($tasks, $exam, $attempt, $foreignNational);
            
            AttemptAnswers::insert($examVariant);
            //$student->tokens()->delete();
            $foreignNational->token = $foreignNational->createToken(
                TokenAbilities::ExamAccess->value,
                [TokenAbilities::ExamAccess->value],
                Carbon::now()->addMinutes($examDuration + 1)
            )->plainTextToken;
            $attempt->load(['answers.taskVariant.answers', 'foreignNational']);
            return $attempt;
        });
        return $this
            ->created(new AttemptResource($examAttempt)
                ->additional(['token' => $foreignNational->token])
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

        $attemptAnswers =  $attempt->answers()->with('taskVariant')
                                        ->whereHas('taskVariant.task', function(Builder $query){
                                            $query->where('type', TaskType::Speaking);
                                        })
                                        ->get();
        $taskVariants=$attemptAnswers->pluck('taskVariant');
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
        return $this->ok(AttemptAnswerResource::collection($uncheckedAnswers));
    }

    public function toCheck(){
        $unCheckedAttempts = Attempt::where('status', AttemptStatus::Finished)->get();
        return AttemptResource::collection($unCheckedAttempts);
    }

    public function full(Attempt $attempt){
        $full = $attempt->load(['answers.taskVariant.answers', 'foreignNational']);
        return $this->ok( new AttemptResource($full)); 
    }
}
