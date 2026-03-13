<?php

namespace App\Actions\Attempt;

use App\Actions\Exam\VerifyExamCodeAction;
use App\Actions\Attempt\GenerateExamVariantAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Attempt\AttemptResource;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class StartExamSessionAction{

    public function __construct(
        protected VerifyExamCodeAction $verifyCode,
        protected GenerateExamVariantAction $generateExamVariant
    ){}
    public function execute(Student $student){
        DB::transaction(function () use($student){
            $exam = Exam::with('examType.blocks.subblocks.tasks.variants')
                        ->find($student->exam_id);

            
            $attempt =  $this->createAttempt($student, $exam->examType->duration, $exam);

            $tasks = $exam->examType->blocks
                ->pluck('subblocks')
                ->flatten()
                ->pluck('tasks')
                ->flatten(); 
            $examVariant = $this->generateExamVariant->execute($tasks, $exam, $attempt, $student);
            StudentAnswer::insert($examVariant);
        });
    }

    protected function createAttempt(Student $student, int $examDuration, Exam $exam){
        $hasAttempt = $student->attempts()->where('exam_id', $exam->id)->exists();
        if($hasAttempt){
            throw new BusinessException('Сущестует текущая попытка экзамен');
        }
        return Attempt::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'organization_id' => $exam->organization_id
            ]);
    }

    
}