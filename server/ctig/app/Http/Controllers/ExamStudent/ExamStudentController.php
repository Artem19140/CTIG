<?php

namespace App\Http\Controllers\ExamStudent;

use App\Actions\Exam\EnrollStudentToExamAction;
use App\Http\Controllers\Controller;
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
            'studentId' => ['required', 'integer'],
        ]);
        $enrollStudentToExam->execute($exam, request()->input('studentId'));
        return $this->created();
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
