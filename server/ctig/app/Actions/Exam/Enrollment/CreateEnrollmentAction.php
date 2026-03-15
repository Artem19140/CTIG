<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use App\Actions\Student\CreateStudentStatementAction;

final class CreateEnrollmentAction{
    public function __construct(
        protected CreateStudentStatementAction $createStudentStatement
    ){}
    public function execute(Exam $exam, int $studentId, User $user){
        $student = Student::find($studentId);
        
        if(!$student){
            throw new EntityNotFoundExсeption('Студент');
        }
        
        $studentAge = Carbon::parse($student->date_birth)->age;
        if($studentAge < 18){
            throw new BusinessException('Запись возможна только с 18 лет');
        }

        if($exam->isPassed() || $exam->isGoing()){
            throw new BusinessException('Экзмен уже прошел или идет');
        }

        if($exam->is_cancelled){
            throw new BusinessException('Экзамен отменен');
        }

        $exam->load(['students']);
        $students=$exam->students;

        if($students->contains($student)){
            throw new BusinessException('Запись уже существует');
        }
        
        if($students->count() >= $exam->capacity){
            throw new BusinessException('Запись уже заполена');
        }
            
        $studentExamsConflict = $student->exams()->where('begin_time', '<=', $exam->end_time)
                                        ->where('end_time', '>=', $exam->begin_time)
                                        ->where('is_cancelled', false)
                                        ->exists();

        if($studentExamsConflict){
            throw new BusinessException('На это время у студента уже существует запись');
        }

        $exam->students()->attach($student, [
            'reg_number' => 124532, 
            'creator_id' => $user->id,
            'organization_id' => $user->organization_id
        ]);
        //return $this->createStudentStatement->execute($exam->id, $student,$user);
    }
}