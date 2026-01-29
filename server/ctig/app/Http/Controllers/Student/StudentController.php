<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Student\StudentPostRequest;
use App\Models\Student;
use App\Http\Resources\Student\StudentResource;

class StudentController extends Controller 
{

    public function index(){
        return StudentResource::collection(Student::all());
    }

    public function store(StudentPostRequest $request ): JsonResponse{
        if($request->input('birth_date'))//<18 Business
        $student = Student::where("passport_number", $request->get('passportNumber'))
                            ->where("passport_series", $request->get('passportSeries'))
                            ->first();
        if($student){
            return response()->json(["result" => "exist"]);
        }
        Student::create($request->all());
        return response()->json(["result" => "ok"]);
    }

    public function show(int $id){
        $student = Student::findOrFail($id);
        return new StudentResource($student);
    }

    
    

    public function update(Student $student)
    {
        //
    }

    public function destroy(Student $student)
    {
        //
    }
}
