<?php

namespace App\Http\Controllers\Exam;

use App\Enums\ExamStatus;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\User;
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
        //Перенести логику из formRequest
        $user = $request->user();
        $examDuration = ExamType::findOrFail(request()->input('exam_type_id'))->duration;
        $examBeginTime = Carbon::parse(request()->input('begin_time'));
        $examEndTime = $examBeginTime->copy()->addMinutes($examDuration);

        $examGroup = Exam::where('status', ExamStatus::Completed)  //считать группу на момент начала экзамена, как и сессию
                                ->where('begin_time', '<', $examBeginTime->copy()->endOfDay())
                                ->where('begin_time', '>', $examBeginTime->copy()->startOfDay())
                                ->count() + 1; 

        $examSessionNumber = Exam::whereBetween('exam_date',
                                [
                                            $examBeginTime->copy()->startOfYear(),
                                            $examBeginTime->copy()->subDay()->endOfDay()
                                        ])
                                ->distinct('exam_date')
                                ->count() + 1;
        
        if($examBeginTime < now()){
            throw new BusinessException('Экзамен нельзя создать на прошедшие даты');
        }
        $conflictExamsExist = Exam::where('address_id', request()->input('address_id'))
                            ->where('begin_time', '<=', $examEndTime)
                            ->where('end_time', '>=', $examBeginTime)
                           ->exists(); 
        if($conflictExamsExist){
            throw new BusinessException('В это время будет проходить другой экзамен');
        }


        $paralellExams = Exam::where('address_id', request()->input('address_id'))
                        ->where('begin_time', '<=', $examEndTime)
                        ->where('end_time', '>=', $examBeginTime)
                        ->get(); 
                        
        $paralellExams->load('testers');
        foreach($paralellExams as $exam){ 
            foreach($exam->testers as $busyTester){
                if( \in_array($busyTester->id,$request->input('testers')) ){
                    throw new BusinessException('Тестер '.$busyTester->surname.' '.$busyTester->name.' записан на другом экзамене');
                }
            }
        }
        //Транзакция!
        $exam = Exam::create(
            [
                'begin_time' => $request->begin_time,
                'address_id' => $request->address_id,
                'capacity' => $request->capacity,
                'exam_type_id' => $request->exam_type_id,
                'comment' => $request->comment,
                'creator_id'=> $user->id,
                'group'=>$examGroup,
                'session_number' => $examSessionNumber,
                'end_time' => $examEndTime,
                'exam_date' => $examBeginTime->copy()->toDate()
            ]
        );
        
        $exam->testers()->attach($request->input('testers'));
        return response()->json(["result" => "ok"]);
    }

    public function show(Exam $exam): ExamResource
    {
        //$exam->load('students'); //в параметр добавь что-нибудь, чтобы со списком и без получать
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