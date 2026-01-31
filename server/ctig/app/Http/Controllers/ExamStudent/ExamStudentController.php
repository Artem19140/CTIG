<?php

namespace App\Http\Controllers\ExamStudent;

use App\Enums\ExamStatus;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;
use App\Models\Student;

class ExamStudentController extends Controller
{

    public function index(Exam $exam)
    {
        $exam->load('students');
        return new ExamResource($exam);
    }

    public function store(int $examId)
    {
        $student = Student::findOrFail(request('studentId')); // Валидация на целое число
        $exam = Exam::findOrFail( $examId); // Валидация на целое число
        $studentAge = Carbon::parse($student->date_birth)->age;
        if($studentAge < 18){
            throw new BusinessException('Запись возможна только с 18 лет');
        }

        if($exam->status !== ExamStatus::Pending){
            throw new BusinessException('На данный экзамен записи уже нет');
        }

        if($exam->exam_date < now()){
            throw new BusinessException('Данный экзамен уже прошел');
        }

        $studentExist = $exam->students()
            ->wherePivot('student_id', $student->id)->exists();

        if($studentExist){
            throw new BusinessException('Запись уже существует');
        }

        if($exam->students()->count() >= $exam->capacity){
            throw new BusinessException('Запись уже заполена');
        }

        $studentExamsConflict = $student->exams()->where('begin_time', '<=', $exam->end_time)
                                        ->where('end_time', '>=', $exam->begin_time)
                                        ->where('status', ExamStatus::Pending)
                                        ->exists();

        if($studentExamsConflict){
            throw new BusinessException('На это время у студента уже существует запись');
        }

        $exam->students()->attach($student);
        return response()->json(['message' => 'Студент добавлен'], 201);
    }

    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
