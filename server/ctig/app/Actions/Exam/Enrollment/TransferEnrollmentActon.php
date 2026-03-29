<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use DB;

class TransferEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment
    ){}
    public function exectute(int $oldExamId, int $newExamId, ForeignNational $foreignNational, User $user){
        $oldExam = Exam::find($oldExamId);
        $newExam = Exam::find($newExamId);

        if($oldExam->isCompleted() || $oldExam->isGoing()){
            throw new BusinessException('Нельзя перенести запись с прошедшего или идущего экзамена');
        }

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