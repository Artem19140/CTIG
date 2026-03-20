<?php

namespace App\Http\Controllers\Web\Student;

use App\Actions\Student\CreateStudentStatementAction;
use App\Actions\Student\CreateStudentWithExamEnrollmentAction;
use App\Actions\Student\GetStudentsListAction;
use App\Http\Requests\Student\StudentIndexRequest;
use App\Http\Requests\Student\StudentPostRequest;
use App\Models\Student;
use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController 
{

    public function index(StudentIndexRequest $request, GetStudentsListAction $getStudentsList){
        $students = $getStudentsList->execute($request->validated() ?? []);
        return Inertia::render('Students/Students', [
            'students' => StudentResource::collection($students),
            'filters' => request()->all()
            
        ]);
    }

    public function store(
                            StudentPostRequest $request, 
                            CreateStudentWithExamEnrollmentAction $createStudentWithExamEnrollment
                        ){

        $student = $createStudentWithExamEnrollment
                ->execute(
                            $request->validated(),
                            $request->validated('examId'),
                            $request->user()
                        );
        Inertia::flash([
            'success' => 'Студент создан',
            'studentId' => $student->id,
            'redirectUrl' => route('students.application-forms', [
                'student' =>$student->id,
                'examId' => $request->validated('examId'),
            ])
        ]);
        return back();
    }

    public function getApplicationForm (Request $request, Student $student, CreateStudentStatementAction $createStudentStatement){
        $request->validate(['examId' => ['required', 'integer']]);
        $applicationFormPdf = $createStudentStatement->execute($request->input('examId'), $student, $request->user());
        return $applicationFormPdf->stream('exam.pdf');
    }

    public function show(Request $request, Student $student){
        $student->load(['exams' => ['attempts' => function ($query) use($student): void{
            $query->where('student_id', $student->id);
            },
            'examType']
        ]);
        
        return Inertia::flash(['student' => StudentResource::make($student)->resolve()])->back();
    }

    public function update(StudentPostRequest $request, Student $student)
    {
        //return $this->created(new StudentResource($student));
    }

    public function destroy(Student $student)
    {
        $student->delete();
        //return $this->noContent();
    }

}
