<?php

namespace App\Domain\Attempt\Action;


use App\Domain\Counter\GenerateGroupNumberAction;
use App\Domain\Counter\GetSessionNumberQuery;
use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\AttemptAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CreateAttemptAction{
    public function __construct(
        protected GenerateGroupNumberAction $generateGroupNumber,
        protected GetSessionNumberQuery $getSessionNumber,
        protected VerifyCodeAction $verifyCode,
        protected ExamGuard $examGuard
    ){}
    public function execute(string $code):Attempt{
        $attempt =  DB::transaction(function () use($code){
            $enrollment = $this->verifyCode->execute($code);
            $foreignNational = ForeignNational::find($enrollment->foreign_national_id);
            $exam = Exam::find($enrollment->exam_id);
            
            $this->examGuard->ensureNotFinished($exam);
            //$this->examGuard->ensureGoing($exam);
            $this->examGuard->ensureNotCancelled($exam);
            
            $attempt =  $this->createAttempt($foreignNational, $enrollment);
            
            $examVariant = $this->generateExamVariant($exam, $attempt, $foreignNational);

            AttemptAnswer::insert($examVariant);

            $this->initializeExamAttributes($exam);

            return $attempt;
        });
        return $attempt;
    }

    protected function createAttempt(ForeignNational $foreignNational, Enrollment $enrollment):Attempt{
        $hasAttempt = $foreignNational->attempts()->where('exam_id', $enrollment->exam_id)->exists();

        if($hasAttempt){
            throw new BusinessException('Сущестует текущая попытка экзамен');
        }

        return Attempt::create([
                'foreign_national_id' => $foreignNational->id,
                'enrollment_id' => $enrollment->id,
                'exam_id' => $enrollment->exam_id,
                'center_id' => $enrollment->center_id
            ]);
    }

    protected function generateExamVariant(Exam $exam, Attempt $attempt, ForeignNational $foreignNational):array{
        $exam->load('type.blocks.subblocks.tasks.variants');
        $tasks = $exam->type->blocks
            ->pluck('subblocks')
            ->flatten()
            ->pluck('tasks')
            ->flatten(); 
        $groups = [];
        $examVariant = [];
        foreach($tasks as $task){    
            $variants = $task->variants
                        ->where('is_active',true);
                        
            $variant = $variants
                        ->whereIn('group_number', $groups)
                        ->first();

            if(!$variant){
                $variant = $variants->random();
            }

            if($variant->group_number && !\in_array($variant->group_number, $groups)){
                $groups[] = $variant->group_number;
            }

            $examVariant[] = [
                'exam_id' => $exam->id,
                'task_variant_id' => $variant->id,
                'attempt_id' => $attempt->id, 
                'foreign_national_id' =>$foreignNational->id, 
                'center_id' => $exam->center_id
            ];
        }
        return $examVariant;
    }

    protected function initializeExamAttributes(Exam $exam){
        $save = false;
        if(!$exam->group){
            $save = true;
            $exam->group = $this->generateGroupNumber->execute();
        }
        if(!$exam->session){
            $save = true;
            $exam->session = $this->getSessionNumber->execute($exam->begin_time);
        }
        if(!$exam->begin_time_real){
            $save = true;
            $exam->begin_time_real = Carbon::now($exam->center->time_zone);
            $exam->save();
        }
        if($save){
            $exam->save();
        }
    }
}