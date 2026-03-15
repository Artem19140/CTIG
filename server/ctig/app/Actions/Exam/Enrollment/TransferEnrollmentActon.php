<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\Student;
use App\Models\User;
use DB;

class TransferEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment
    ){}
    public function exectute(Exam $exam, Student $student, User $user){
        $isEnrollmentExists = $exam->students()->where('student_id', $student->id)->exists();

        if(!$isEnrollmentExists){
            throw new BusinessException('Такой записи на экзамен не существует');
        }

        if($exam->isPassed() || $exam->isGoing()){
            throw new BusinessException('Нельзя перенести студента с прошедшего или идущего экзамена');
        }

        $newExam = Exam::find(request()->input('examId'));
        if(!$newExam){
            throw new EntityNotFoundExсeption('Экзамен для переноса');
        }
        
        $isEnrollmentExistsNewExam = $newExam->students()->where('student_id', $student->id)->exists();

        if($isEnrollmentExistsNewExam){
            throw new BusinessException('Студент уже имеет запись на экзамене для переноса');
        }

        DB::transaction(function () use($newExam, $student, $exam, $user){
            $exam->students()->detach($student->id);
            $this->createEnrollment->execute($newExam, $student->id, $user);
        });
    }
}