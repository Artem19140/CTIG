<?php

namespace App\Http\Controllers\Web\Student;

use App\Actions\Exam\EnrollStudentToExamAction;
use App\Actions\Student\GetStudentsListAction;
use App\Actions\Student\StudentStoreAction;
use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Http\Requests\Student\StudentIndexRequest;
use App\Models\Exam;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Student\StudentPostRequest;
use App\Models\Student;
use App\Http\Resources\Student\StudentResource;
use Inertia\Inertia;

class StudentController 
{

    public function index(StudentIndexRequest $request, GetStudentsListAction $getStudentsList){
        $students = $getStudentsList->execute($request->validated() ?? []);
        return Inertia::render('Students/Students', [
            'students' => StudentResource::collection($students)
        ]);
    }

    public function store(
                            StudentPostRequest $request, 
                            StudentStoreAction $studentStore,
                            EnrollStudentToExamAction $enrollStudentToExam
                        ){
        DB::transaction(function ()use($studentStore, $request, $enrollStudentToExam) {
            $exam = Exam::find($request->validated('examId'));
            if(!$exam){
                throw new EntityNotFoundExсeption('Экзамен');
            }
            $student = $studentStore->execute($request->validated(), $request->user()->id);
            $enrollStudentToExam->execute($exam, $student->id,$request->user()->id);
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Студент добавлен');
        //return $this->created(new StudentResource($student));
    }

    public function show(Student $student){
        $student->load('attempts.exam.examType');//Мб exams??
        $student->load(['exams.attempts' => function ($query) use($student): void{
            $query->where('student_id', $student->id);
        }]);
        return new StudentResource($student);
    }

    public function update(StudentPostRequest $request, Student $student)
    {
        
        //return $this->created(new StudentResource($student));
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return $this->noContent();
    }

}
