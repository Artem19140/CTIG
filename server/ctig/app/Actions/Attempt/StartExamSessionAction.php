<?php

namespace App\Actions\Attempt;

use App\Actions\Attempt\GenerateExamVariantAction;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\AttemptAnswer;
use Illuminate\Support\Facades\DB;
use App\Actions\Exam\SetSessionAndGroupNumberAction;


class StartExamSessionAction{

    public function __construct(
        protected GenerateExamVariantAction $generateExamVariant,
        protected SetSessionAndGroupNumberAction $setSessionAndGroupNumber
    ){}
    public function execute(ForeignNational $foreignNational):Attempt{
        return DB::transaction(function () use($foreignNational){
            $exam = Exam::with('examType.blocks.subblocks.tasks.variants')
                        ->find($foreignNational->exam_id);
            // if(!$exam->isGoing()){
            //     throw new BusinessException('Начать попытку можно только во время экзамена');
            // }
            
            $attempt =  $this->createAttempt($foreignNational, $exam);

            $tasks = $exam->examType->blocks
                ->pluck('subblocks')
                ->flatten()
                ->pluck('tasks')
                ->flatten(); 
            $examVariant = $this->generateExamVariant->execute($tasks, $exam, $attempt, $foreignNational);
            AttemptAnswer::insert($examVariant);
            if(!$exam->group || !$exam->session){
                $this->setSessionAndGroupNumber->execute($exam);
            }
            
            return $attempt;
        });
    }

    protected function createAttempt(ForeignNational $foreignNational, Exam $exam){
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

    
}