<?php

namespace App\Domain\Exam\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Domain\Exam\Rules\ValidateExamForSave;
use App\Http\Dto\ExamDto;
use App\Models\Exam;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


final class UpdateExamAction{
    public function __construct(
        protected ExamGuard $examGuard,
        protected ValidateExamForSave $validateExamForSave
    ){}
    public function execute(
        Exam $exam, 
        ExamDto $examDto,
    ):void
    {
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotGoing($exam);
        $this->examGuard->ensureNotFinished($exam);

        $this->validateExamForSave->execute($examDto, $exam->id);
        $before = $this->getAttributesToLog($exam);
        $exam = DB::transaction(function () use ($examDto, $exam) {
            
            $exam->update(
                $this->getAttributes($exam, $examDto)
            );
            $exam->examiners()->sync($examDto->examiners);
            $exam->save();
            
            $exam->load(['examiners', 'type', 'address']);
            $exam->loadCount('enrollments');
            
            return $exam;
        });      
        $this->log($exam, $before);
        
    }
    protected function getAttributes(Exam $exam, ExamDto $examDto):array{
        $attributes = [];

        if(!$exam->enrollments()->exists()){
            $attributes = [
                'begin_time' => $examDto->beginTime,
                'address_id' => $examDto->addressId,
                'capacity' => $examDto->capacity,
                'exam_type_id' => $examDto->examTypeId,
                'comment' => $examDto->comment,
                'end_time' => $examDto->beginTime->copy()->addMinutes($exam->duration)
            ];
        }else{
            if($exam->enrollments()->count() > $examDto->capacity){
                throw ValidationException::withMessages([
                    'capacity' => 'Запись на экзамен превышает вместимость'
                ]);
            }
            $attributes['capacity'] = $examDto->capacity;
        }
        $attributes['comment'] = $examDto->comment;
        
        return $attributes;
    }

    protected function log(Exam $exam, array $before):void{
        Log::info('exam_updated', [
            'exam_id' => $exam->id,
            'changes' => [
                'before' => $before,
                'after' =>  $this->getAttributesToLog($exam)
            ]
        ]);
    }

    protected function getAttributesToLog(Exam $exam):array{
        $attributes = [
            'begin_time',
            'address_id',
            'capacity',
            'exam_type_id',
            'comment'
        ];
        return $exam->only($attributes);
    }
}