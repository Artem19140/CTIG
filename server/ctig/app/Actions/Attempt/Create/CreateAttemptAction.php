<?php

namespace App\Actions\Attempt\Create;

use App\Actions\Attempt\Create\VerifyCodeAction;
use App\Actions\Counter\GetGroupNumberAction;
use App\Actions\Counter\GetSessionNumberAction;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\AttemptAnswer;
use App\Validation\ExamValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CreateAttemptAction{
    public function __construct(
        protected GetGroupNumberAction $getGroupNumber,
        protected GetSessionNumberAction $getSessionNumber,
        protected VerifyCodeAction $verifyCode,
        protected ExamValidation $examValidation
    ){}
    public function execute(string $code):Attempt{
        $attempt =  DB::transaction(function () use($code){
            $foreignNational = $this->verifyCode->execute($code);

            $exam = Exam::find($foreignNational->exam_id);
            
            $this->examValidation->ensureNotCompleted($exam);
            $this->examValidation->ensureGoing($exam);
            $this->examValidation->ensureNotCancelled($exam);
            
            $attempt =  $this->createAttempt($foreignNational, $exam);
            
            $examVariant = $this->generateExamVariant($exam, $attempt, $foreignNational);

            AttemptAnswer::insert($examVariant);

            $this->initializeExamAttributes($exam);

            return $attempt;
        });
        return $attempt;
    }

    protected function createAttempt(ForeignNational $foreignNational, Exam $exam):Attempt{
        $hasAttempt = $foreignNational->attempts()->where('exam_id', $exam->id)->exists();

        if($hasAttempt){
            throw new BusinessException('Сущестует текущая попытка экзамен');
        }

        return Attempt::create([
                'foreign_national_id' => $foreignNational->id,
                'exam_id' => $exam->id,
                'organization_id' => $exam->organization_id
            ]);
    }

    protected function generateExamVariant(Exam $exam, Attempt $attempt, ForeignNational $foreignNational):array{
        $exam->load('examType.blocks.subblocks.tasks.variants');
        $tasks = $exam->examType->blocks
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
                'organization_id' => $exam->organization_id
            ];
        }
        return $examVariant;
    }

    protected function initializeExamAttributes(Exam $exam){
        $save = false;
        if(!$exam->group){
            $save = true;
            $exam->group = $this->getGroupNumber->execute();
        }
        if(!$exam->session){
            $save = true;
            $exam->session = $this->getSessionNumber->execute($exam->begin_time);
        }
        if(!$exam->begin_time_real){
            $exam->begin_time_real = Carbon::now($exam->organization->time_zone);
            $exam->save();
        }
        if($save){
            $exam->save();
        }
    }
}