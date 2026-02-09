<?php

namespace App\Http\Controllers\ExamCode;

use App\Models\ExamCode;
use App\Models\Exam;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Exception\BuilderNotFoundException;
use App\Http\Resources\ExamCode\ExamCodeResource;
use Throwable;
use App\Actions\Exam\CreateStudentsCodesForExamAction;

class ExamCodeController extends Controller
{
    public function index()
    {
        // после использования - сразу удаляй из бд, код одноразовый
    }

    public function store(Exam $exam, CreateStudentsCodesForExamAction $createStudentsCodesForExam)
    {
        $createStudentsCodesForExam->execute($exam);
        return $this->created();
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

    public function create(Exam $exam, CreateStudentsCodesForExamAction $createStudentsCodesForExam){
        $createStudentsCodesForExam->execute($exam);
        return $this->created();
        DB::transaction(function () use ($examId) {
            $exam = Exam::findOrFail($examId);
            //Экзамен еще не прошел, время за пол часа, есть студенты у него, 1 к 1 можно сделать отношение, а не 1 ко многу 
            $exam->load('students');
            $students = $exam->students;
            if($students->isEmpty()){
                throw new BuilderNotFoundException('Нет студентов');
            }

            if($exam->expired_at <= now()){
                throw new BuilderNotFoundException('Экзамен уже прошел');
            }

            $diffInMinutes = $exam->expired_at->diffInMinutes(now(), false);
            if($diffInMinutes >= 30){
                throw new BuilderNotFoundException('Код можно сформировать минимум за 30 минут до экзамена');
            }
            //$students->load('code');
            // if($exam->isCodesGeterated()){
            //     return ExamCodeResource::collection($students); //Создание файла
            // } если коды сделаны, то тогда вернуть обратно список

            foreach ($exam->students as $student) {
                do{
                    $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); //коды могут повторяться!!!!! можно сдеалть в связке с экзаменом уникальность!
                    $isUnique =  ExamCode::where('code', '=', $code)->exists();//мб проверка еще на is_used?
                }while($isUnique);
                $student->code = $code;
                $student->save();
            }
            //вернуть коды
        });
    }    
}
