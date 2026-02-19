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
        $student = Student::create([
            'surname' => $request->validated('surname'),
            'name'=> $request->validated('name'),
            'patronymic'=> $request->validated('patronymic'),
            'date_birth'=> $request->validated('dateBirth'),
            'surname_latin'=> $request->validated('surnameLatin'),
            'name_latin'=> $request->validated('nameLatin'),
            'patronymic_latin'=> $request->validated('patronymicLatin'),
            'passport_number'=> $request->validated('passportNumber'),
            'passport_series'=> $request->validated('passportSeries'),
            'issued_by'=> $request->validated('issuedBy'),
            'migration_card_requisite'=> $request->validated('migrationCardRequisite'),
            'issues_date'=> $request->validated('issuesDate'),
            'address_reg'=> $request->validated('addressReg'),
            'citizenship'=> $request->validated('citizenship'),
            'phone'=> $request->validated('phone'),
            'creator_id'=>$request->user()->id
        ]);
        return $this->created(new StudentResource($student));
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
