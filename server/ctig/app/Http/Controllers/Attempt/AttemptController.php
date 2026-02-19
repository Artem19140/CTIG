<?php

namespace App\Http\Controllers\Attempt;

use App\Enums\AttemptStatusEnum;
use App\Enums\TaskTypeEnum;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Exam;
use App\Models\Attempt;
use App\Models\StudentAnswer;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $student=$request->user();
        $hasCurrentExamAttempt = Attempt::where('student_id', $student->id)
                                    ->where('status',  AttemptStatusEnum::Started)
                                    ->exists();
                                    
        if($hasCurrentExamAttempt){
            throw new BusinessException('Существует текущая попытка экзамена');
        }
        
        $exam = Exam::with('examType.blocks.subblocks.tasks.variants.answers')
                    ->find($student->exam_id);

        $tasks = DB::transaction(function () use($student, $exam) {
            $examDuration = $exam->examType->duration;
            $attempt = Attempt::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'expired_at' => Carbon::now()->addMinutes($examDuration),
                    'started_at' => Carbon::now()
                ]);
            $examVariant = [];
            $tasks = $exam->examType->blocks
                ->pluck('subblocks')
                ->flatten()
                ->pluck('tasks')
                ->flatten();
            $groups = [];

            foreach($tasks as $task){    
                $variants = $task->variants
                            ->where('is_active',true);
                            
                $variant = $variants
                            ->whereIn('group_number', $groups)
                            ->first();

                if(!$variant){
                    $variant = $variants->random();
                }

                if($variant->group_number && !in_array($variant->group_number, $groups)){
                    $groups[] = $variant->group_number;
                }

                $examVariant[] = [
                    'exam_id' => $exam->id,
                    'task_variant_id' => $variant->id,
                    'attempt_id' => $attempt->id, 
                    'student_id' =>$student->id, 
                ];
            }
            StudentAnswer::insert($examVariant);
            $student->tokens()->delete();
            $student->token = $student->createToken(
                'exam-token',
                ['exam:access'],
                Carbon::now()->addMinutes($examDuration + 1)
            )->plainTextToken;
            return StudentAnswer::where('attempt_id', $attempt->id)->get(); 
        });
        return $this
            ->created(StudentAnswerResource::collection($tasks)
                ->additional(['token' => $student->token])
            );
    }

    public function show(Attempt $attempt)
    {
        $attempt->load('answers');
        return new AttemptResource($attempt);
    }

    public function ban(Attempt $attempt)
    {
        $attempt->status = AttemptStatusEnum::Banned;
        $attempt->save();
    }

    public function speaking(Attempt $attempt){
        $studentAnswer = StudentAnswer::whereHas('taskVariant.task', function(Builder $query){
                                            $query->where('type', TaskTypeEnum::Speaking);
                                        })
                                        ->get();
        $taskVariants=$studentAnswer->pluck('taskVariant');
        return TaskVariantResource::collection($taskVariants);
    }

    public function finish(Attempt $attempt)
    {
        //В общем, нужно смотерть, если есть задания с ручной проверкой - не ставь checked
        //Если остались задания без ответа и без ручной - проверки, то ставь 0 в mark и is_checked = true
        if($attempt->isExpired()){
            $attempt->finish();
            throw new BusinessException('Время экзамен вышло');
        }
        DB::transaction(function() use($attempt){
            $attempt->finish();
            request()->user()->tokens()->delete();
            //если есть задания с ручной проверкой - не ставь checked
            $attempt->save();
        });
        return $this->noContent();
    }
}
