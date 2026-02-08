<?php

namespace App\Actions\Exam;

use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use App\Exceptions\BusinessException;

final class CreateExamAction{
    public function handle($examDto, $creatorId){
        $examType =  ExamType::find(request()->input('examTypeId'));
        $examAddress = Address::find(request()->input('addressId'));
        
        if(!$examType){
            throw new BusinessException('Выбранный идентификатор типа экзамена не существует.');
        }

        if(!$examAddress){
            throw new BusinessException('Выбранный идентификатор адреса не существует.');
        }

        if(!$examAddress->is_actual){
            throw new BusinessException('Адрес проведения экзамена неактуален');
        }

        if(!$examType->is_actual){
            throw new BusinessException('Данный экзамена неактуален');
        }

       
        $examBeginTime = Carbon::parse(request()->input('beginTime'));

        if($examBeginTime < now()){
            throw new BusinessException('Экзамен нельзя создать на прошедшие даты');
        }

        $examDuration = $examType->duration;
        $examEndTime = $examBeginTime->copy()->addMinutes($examDuration);

        
       
        $conflictExamsExist = Exam::where('address_id', $examAddress->id)
                            ->where('begin_time', '<=', $examEndTime)
                            ->where('end_time', '>=', $examBeginTime)
                            //->whereHas() можно сразу и тесторов получить, вот
                           ->exists(); 

        if($conflictExamsExist){
            throw new BusinessException('В это время будет проходить другой экзамен');
        }


        $paralellExams = Exam::where('address_id', $examAddress->id)
                        ->where('begin_time', '<=', $examEndTime)
                        ->where('end_time', '>=', $examBeginTime)
                        ->get(); 
                        
        $paralellExams->load('testers');
        foreach($paralellExams as $exam){ 
            foreach($exam->testers as $busyTester){
                if( \in_array($busyTester->id,$examDto->testers) ){
                    throw new BusinessException('Тестер '.$busyTester->surname.' '.$busyTester->name.' записан на другом экзамене');
                }
            }
        }
        //Транзакция!
        $exam = Exam::create(
            [
                'begin_time' => $examDto->beginTime,
                'address_id' => $examDto->addressId,
                'capacity' => $examDto->capacity,
                'exam_type_id' => $examDto->examTypeId,
                'comment' => $examDto->comment,
                'creator_id'=> $creatorId,
                'end_time' => $examEndTime,
                'exam_date' => $examBeginTime->copy()->toDate()
            ]
        );
        
        $exam->testers()->attach($examDto->testers);
    }
}