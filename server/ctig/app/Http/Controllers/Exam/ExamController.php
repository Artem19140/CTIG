<?php

namespace App\Http\Controllers\Exam;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamRequest;

class ExamController extends Controller
{
    
    public function index()
    {
        //Не все, а по фильтрам
        return ExamResource::collection(Exam::all());
    }

    public function store(ExamRequest $request)
    {       
        
        $user = $request->user();
        $examDuration = ExamType::findOrFail(request()->input('exam_type_id'))->duration;
        $examBeginTime = Carbon::parse(request()->input('begin_time'));
        $examEndTime = $examBeginTime->copy()->addMinutes($examDuration);
        if($examBeginTime < now()){
            throw new BusinessException('Экзамен нельзя создать на прошедшие даты');
        }

        $conflictExams = Exam::where('exam_address_id', request()->input('exam_address_id'))
                            ->where('begin_time', '<', $examEndTime)
                            ->where('end_time', '>', $examBeginTime)
                           ->exists(); 

        if($conflictExams){
            throw new BusinessException('В это время будет проходить другой экзамен');
        }

        $examGroup = Exam::whereDate('begin_time', $examBeginTime->copy()->toDate())->count() + 1;

        $examSessionNumber = Exam::whereBetween('exam_date',
                                [now()->startOfYear(), now()->endOfYear()])
                                ->distinct()
                                ->count();
        echo $examSessionNumber; //Дает дубликаты
        $exam = Exam::create(
            [
                'begin_time' => $request->begin_time,
                'exam_address_id' => $request->exam_address_id,
                'capacity' => $request->capacity,
                'exam_type_id' => $request->exam_type_id,
                'comment' => $request->comment,
                'creator_id'=> $user->id,
                'group'=>$examGroup,
                'session_number' => 2,
                'end_time' => $examEndTime,
                "exam_date" => $examBeginTime->copy()->toDate()
            ]
        );
        $exam->testers()->attach($request->input('testers'));
        return response()->json(["result" => "ok"]);
    }

    public function show(int $examId): ExamResource
    {
        $exam = Exam::findOrFail($examId);
        $exam->load('students'); //в параметр добавь что-нибудь, чтобы со списком и без получать
        return new ExamResource($exam);
    }

    public function update(Request $request, Exam $exam)
    {
        //
    }

    public function destroy(Exam $exam)
    {
        //
    }
}
//Проверка, что в это время нет экзаменов
//Проверка, что тестер не занят(По длительности экзамена)
//Проверка, что адрес актуальный и по нему проводятся экзамен
//Экзамен не конфликтует по времени и адресу с другими экзаменами
//Что такой тип экзамена проводится
//Что вместительность > 0
//Экзамен не в прошлом создается
//Есть права на создание экзамена
//Что тестор сущестует, и он может сидеть на экзамене есть

//Группу и сессию нужно высчитывать, либо после экзамена уже