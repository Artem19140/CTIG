<?php

namespace App\Actions\Student;

use App\Actions\Exam\EnrollStudentToExamAction;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\User;
use DB;

class CreateStudentWithExamEnrollmentAction{
    public function __construct(
        protected StoreStudentAction $storeStudent,
        protected EnrollStudentToExamAction $enrollStudentToExam,
    ){}
    public function execute(array $studentData, int $examId, User $user){
        $exam = Exam::find($examId);
        if(!$exam){
            throw new EntityNotFoundExсeption('Экзамен');
        }
        return DB::transaction(function () use($studentData, $user, $exam) {
            $student = $this->storeStudent->execute($studentData, $user->id);
            $this->enrollStudentToExam->execute($exam, $student->id, $user);
            return $student;
        });//Удалить файлы загруженные при исключении?
        

    }
}