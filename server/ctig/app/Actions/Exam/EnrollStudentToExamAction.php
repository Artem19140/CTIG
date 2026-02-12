<?php

namespace App\Actions\Exam;

use App\Enums\ExamStatus;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\Eloquent\Builder;

final class EnrollStudentToExamAction{
    public function execute(Exam $exam, int $studentId){
        $student = Student::find($studentId);
        
        if(!$student){
            throw new EntityNotFoundExсeption('Студент');
        }
        
        $studentAge = Carbon::parse($student->date_birth)->age;
        if($studentAge < 18){
            throw new BusinessException('Запись возможна только с 18 лет');
        }
        
        if($exam->status !== ExamStatus::Expected){
            throw new BusinessException('На данный экзамен записи уже нет');
        }

        if($exam->isPassed()){
            throw new BusinessException('Экзмен уже прошел');
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
                                        ->where('status', ExamStatus::Expected)
                                        ->exists();

        if($studentExamsConflict){
            throw new BusinessException('На это время у студента уже существует запись');
        }

        $exam->students()->attach($student);
        return true;
    }
}