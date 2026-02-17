<?php

namespace App\Actions\Exam;

use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\Eloquent\Builder;

final class CreateExamAction{
    public function handle($examDto, $creatorId){
        $examType =  ExamType::find($examDto->examTypeId);
        $examAddress = Address::find($examDto->addressId);
        
        if(!$examType){
            throw new EntityNotFoundExсeption('Тип экзамена');
        }

        if(!$examAddress){
            throw new EntityNotFoundExсeption('Адрес');
        }

        if(!$examAddress->is_active){
            throw new BusinessException('Адрес проведения экзамена неактуален');
        }

        if(!$examType->is_active){
            throw new BusinessException('Данный экзамен неактуален');
        }

        $examBeginTime = Carbon::parse($examDto->beginTime);

        if($examBeginTime < Carbon::now()){
            throw new BusinessException('Экзамен нельзя создать на прошедшие даты');
        }

        $examDuration = $examType->duration;
        $examEndTime = $examBeginTime->copy()->addMinutes($examDuration);

        $hasConflictExam = Exam::where('address_id', $examDto->addressId)
                            ->where('begin_time', '<=', $examEndTime)
                            ->where('end_time', '>=', $examBeginTime)
                            ->exists(); 

        if($hasConflictExam){
            throw new BusinessException("В это время по данному адресу уже проводится экзамен");
        }
        
        $hasConflictTesters = Exam::where('begin_time', '<=', $examEndTime)
                            ->where('end_time', '>=', $examBeginTime)
                            ->whereHas('testers', function (Builder $query) use ($examDto): void {
                                $query->whereIn('users.id', $examDto->testers);
                            })
                            ->exists();
        
        if($hasConflictTesters){
            throw new BusinessException("Один или несколько тестеров заняты в это время");
        }
         
        $exam = DB::transaction(function () use ($examDto, $creatorId,$examBeginTime, $examEndTime) {
            $exam = Exam::create(
            [
                    'begin_time' => $examDto->beginTime,
                    'address_id' => $examDto->addressId,
                    'capacity' => $examDto->capacity,
                    'exam_type_id' => $examDto->examTypeId,
                    'comment' => $examDto->comment,
                    'creator_id'=> $creatorId,
                    'end_time' => $examEndTime,
                    'date' => $examBeginTime->copy()->toDate()
                ]
            );
        
            $exam->testers()->attach($examDto->testers);
            return $exam;
        });        
        return $exam;
    }
}