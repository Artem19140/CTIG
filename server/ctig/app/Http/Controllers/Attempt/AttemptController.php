<?php

namespace App\Http\Controllers\Attempt;

use App\Enums\AttemptStatusEnum;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
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
                                    
        // if($hasCurrentExamAttempt){
        //     throw new BusinessException('Существует текущая попытка экзамена');
        // }
        $exam=Exam::with('examType.blocks.subblocks.tasks.variants.answers')->find($student->exam_id);

        
        DB::transaction(function () use($student, $exam) {
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
        foreach($tasks as $task){
            $variantId = $task->variants->where('is_active',true)->random();

            $examTaskVariant = [
                'exam_id' => $exam->id,
                'block_id' => 1, //$task->subblock->block->id
                'task_variant_id' => $variantId->id,
                'attempt_id' => $attempt->id, 
                'student_id' =>$student->id, 
            ];

            $examVariant[] =  $examTaskVariant;
        }
        StudentAnswer::insert($examVariant);
        $tasks = StudentAnswer::where('attempt_id', $attempt->id)->get();
        return $this->created(StudentAnswerResource::collection($tasks)); 
        });
        
        $examDuration = $exam->examType->duration;
        $student->tokens()->delete();
        $student->createToken(
            'exam-token',
            ['exam:access'],
            Carbon::now()->addMinutes($examDuration + 1)
        );
        //удалить код
        return $this->created();
    }

    public function show(Attempt $attempt)
    {
        //
    }


    public function update(Request $request, Attempt $attempt)
    {
        //
    }

    public function destroy(Attempt $attempt)
    {
        //
    }
}
