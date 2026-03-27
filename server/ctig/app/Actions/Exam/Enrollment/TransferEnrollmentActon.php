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
    public function exectute(Exam $exam, ForeignNational $foreignNational, User $user){
        $isEnrollmentExists = $exam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();

        if(!$isEnrollmentExists){
            throw new BusinessException('Такой записи на экзамен не существует');
        }

        if($exam->isCompleted() || $exam->isGoing()){
            throw new BusinessException('Нельзя перенести запись с прошедшего или идущего экзамена');
        }

        $newExam = Exam::find(request()->input('examId'));
        if(!$newExam){
            throw new EntityNotFoundExсeption('Экзамен для переноса');
        }
        
        $isEnrollmentExistsNewExam = $newExam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();

        if($isEnrollmentExistsNewExam){
            throw new BusinessException('ИГ уже имеет запись на экзамене для переноса');
        }

        DB::transaction(function () use($newExam, $foreignNational, $exam, $user){
            $exam->foreignNationals()->detach($foreignNational->id);
            $this->createEnrollment->execute($newExam, $foreignNational->id, $user);
        });
    }
}