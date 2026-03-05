<?php

namespace App\Http\Controllers\Api\ExamStudent;

use App\Actions\Exam\EnrollStudentToExamAction;
use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Http\Controllers\Api\Controller;
use App\Models\Student;
use DB;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;

class ExamStudentController extends Controller
{

    public function index(Exam $exam)
    {
        $exam->load('students');
        return new ExamResource($exam);
    }

    public function store(Exam $exam, EnrollStudentToExamAction $enrollStudentToExam)
    {
        request()->validate([
            'studentId' => ['required', 'integer', 'min:1'],
        ]);
        $enrollStudentToExam->execute($exam, request()->input('studentId'));
        return $this->created();
    }

    public function transfer(Exam $exam, Student $student, EnrollStudentToExamAction $enrollStudentToExam)
    {
        request()->validate([
            'examId' => ['required', 'integer', 'min:1'],
        ]);

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

        DB::transaction(function () use($enrollStudentToExam, $newExam, $student, $exam){
            $exam->students()->detach($student->id);
            $enrollStudentToExam->execute($newExam, $student->id);
        });
        return $this->noContent();
    }


    public function destroy(Exam $exam, Student $student)
    {
        $isEnrollmentExists = $exam->students()->where('student_id', $student->id)->exists();
        if(!$isEnrollmentExists){
            throw new BusinessException('Такой записи на экзамен не существует');
        }

        if($exam->isPassed() || $exam->isGoing()){
            throw new BusinessException('Невозможно отменить запись на прошедший или идущий экзамен');
        }
        $exam->students()->detach($student->id);
        return $this->noContent();
    }
}
