<?php

namespace App\Http\Controllers\ExamStudent;

use App\Http\Controllers\Controller;
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


    public function store(int $id)
    {
        //Записи нет у данного мигранта(чтоб 2 раза не записать) 422
        //он старше 18
        //что экзамен и мигрант сущестуют
        //что у мигранта в это время нет экзамен в другом месте
        //что существует и актуален(проводится) экзамен
        //экзамен не прошел(только будет)
        //места свободные еще есть!!! иначе 422 мест нет
        $student = Student::findOrFail(request('studentId'));
        $exam = Exam::findOrFail( $id);
        $studentExist= $exam->students()
            ->wherePivot('student_id', $student->id)->exists();
        if($studentExist){
            return response()->json(['message' => 'Запись уже есть'], 409);
        }
        $exam->students()->attach($student);
        return response()->json(['message' => 'Студент добавлен'], 201);
    }


    public function show(string $id)
    {
        return response()->json(['ok'=>$id]);
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
