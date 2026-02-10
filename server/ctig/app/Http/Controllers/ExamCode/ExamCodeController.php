<?php

namespace App\Http\Controllers\ExamCode;

use App\Models\ExamCode;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Actions\Exam\CreateStudentsCodesForExamAction;

class ExamCodeController extends Controller
{
    public function index()
    {
        // после использования - сразу удаляй из бд, код одноразовый
    }

    public function store(Exam $exam, CreateStudentsCodesForExamAction $createStudentsCodesForExam)
    {
        //$studentsWithCodes = $createStudentsCodesForExam->execute($exam);
        return $createStudentsCodesForExam->execute($exam);
        //return $this->created($studentsWithCodes);
    }

    public function show(ExamCode $examCode)
    {
        //
    }

    public function update(Request $request, ExamCode $examCode)
    {
        //
    }

    public function destroy(ExamCode $examCode)
    {
        //
    }
    
    public function verify($examId){
        //У студента ищу код его
    }
}
