<?php

namespace App\Http\Controllers\Student;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Student\StudentPostRequest;
use App\Models\Student;
use App\Http\Resources\Student\StudentResource;

class StudentController extends Controller 
{

    public function index(){
        return StudentResource::collection(Student::all()); //доп параметры 
    }

    public function store(StudentPostRequest $request): JsonResponse{
        $age = Carbon::parse($request->input('dateBirth'))->age;
        if($age < 18){
            throw new BusinessException('На экзамен можно записывать с 18 лет');
        }
        $studentExists = Student::where("passport_number", $request->input('passportNumber'))
                            ->where("passport_series", $request->input('passportSeries'))
                            ->exists();
        if($studentExists){
            throw new BusinessException('Студент уже существует');
        }
        Student::create([
            'surname' => $request->input('surname'),
            'name'=> $request->input('name'),
            'patronymic'=> $request->input('patronymic'),
            'date_birth'=> $request->input('dateBirth'),
            'surname_latin'=> $request->input('surnameLatin'),
            'name_latin'=> $request->input('nameLatin'),
            'patronymic_latin'=> $request->input('patronymicLatin'),
            'passport_number'=> $request->input('passportNumber'),
            'passport_series'=> $request->input('passportSeries'),
            'issued_by'=> $request->input('issuedBy'),
            'migration_card_requisite'=> $request->input('migrationCardRequisite'),
            'issues_date'=> $request->input('issuesDate'),
            'address_reg'=> $request->input('addressReg'),
            'citizenship'=> $request->input('citizenship'),
            'phone'=> $request->input('phone'),
            'creator_id'=>$request->user()->id
        ]);
        return response()->json(["result" => "ok"]);
    }

    public function show(Student $student){
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
