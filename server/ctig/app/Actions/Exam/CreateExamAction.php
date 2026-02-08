<?php

namespace App\Actions\Exam;

use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use DB;

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

        $paralellExams = Exam::where('begin_time', '<=', $examEndTime)
                            ->where('end_time', '>=', $examBeginTime)
                            ->get(); 
        $conflictExams = $paralellExams->firstWhere('address_id', $examDto->addressId);

        // if($conflictExams){
        //     $conflictExams->load('examType');
        //     $conflictExams->load('address');
        //     $examName =  trim($conflictExams->examType->name);
        //     $time = trim($conflictExams->begin_time);
        //     $address = trim($conflictExams->address->address);
        //     throw new BusinessException("В {$time} по адресу {$address} будет проходить экзамен на {$examName}");
        // }
        
        $paralellExams->load('testers');
        $testers = $paralellExams->pluck('testers')->flatten()->unique('id');
        // dd($examDto->testers, $testers->pluck('id'));
        $busyTesters = $testers->filter(fn($tester) => in_array($tester->id, $examDto->testers));
        if($busyTesters->isNotEmpty()){
            $stringNames = $busyTesters->pluck('surname')->implode(', ');
            throw new BusinessException("Тестер {$stringNames} занята в это время на другом экзамене");
        }

        DB::transaction(function () use ($examDto, $creatorId,$examBeginTime, $examEndTime) {
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
        });        
    }
}