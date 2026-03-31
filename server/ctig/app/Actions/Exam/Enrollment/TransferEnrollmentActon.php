<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use App\Validation\ExamValidation;
use DB;

class TransferEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment,
        protected ExamValidation $examValidation
    ){}
    public function exectute(int $oldExamId, int $newExamId, ForeignNational $foreignNational, User $user){
        $oldExam = Exam::find($oldExamId);
        $newExam = Exam::find($newExamId);

        $this->examValidation->ensureNotCancelled($oldExam);
        $this->examValidation->ensureNotCancelled($newExam);

        $this->examValidation->ensureNotCompleted($oldExam);
        $this->examValidation->ensureNotCompleted($newExam);
        
        $this->examValidation->ensureNotGoing($oldExam);
        $this->examValidation->ensureNotGoing($newExam);

        $oldEnrollment = $oldExam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->first();

        if(!$oldEnrollment){
            throw new BusinessException('Такой записи на экзамен не существует');
        }

        if($oldExam->exam_type_id !== $newExam->exam_type_id ){
            throw new BusinessException('Запись можно перенести только на тот же вид экзамена');
        }

        $isEnrollmentExistsNewExam = $newExam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();

        if($isEnrollmentExistsNewExam){
            throw new BusinessException('ИГ уже имеет запись на экзамене, на который Вы хотите его перенести');
        }

        DB::transaction(function () use($newExam, $foreignNational, $oldExam, $user, $oldEnrollment){
            $oldExam->foreignNationals()->detach($foreignNational->id);
            $this->createEnrollment->execute($newExam, $foreignNational->id, $user, $oldEnrollment->pivot->has_payment);
        });
    }
}