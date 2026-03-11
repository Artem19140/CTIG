<?php

namespace App\Actions\Student;

use App\Actions\Exam\EnrollStudentToExamAction;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use DB;

class CreateStudentWithExamEnrollmentAction{
    public function __construct(
        protected StoreStudentAction $storeStudent,
        protected EnrollStudentToExamAction $enrollStudentToExam,
    ){}
    public function execute(array $studentData, int $examId, int $creatorId){
        $exam = Exam::find($examId);
        if(!$exam){
            throw new EntityNotFoundExсeption('Экзамен');
        }
        return DB::transaction(function () use($studentData, $creatorId, $exam) {
            $student = $this->storeStudent->execute($studentData, $creatorId);
            $this->enrollStudentToExam->execute($exam, $student->id, $creatorId);
            return $student;
        });//Удалить файлы загруженные при исключении?
        

    }
}