<?php

namespace App\Actions\Exam;

use App\Exceptions\EntityNotFoundExсeption;
use App\Http\Dto\ExamDto;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\User;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\Eloquent\Builder;

final class CreateExamAction{
    public function handle(ExamDto $examDto, User $user){
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
                            ->where('is_cancelled', false)
                            ->exists(); 

        if($hasConflictExam){
            throw new BusinessException("В это время по данному адресу уже проводится экзамен");
        }
        
        $hasConflictExaminers = Exam::where('begin_time', '<=', $examEndTime)
                                ->where('end_time', '>=', $examBeginTime)
                                ->whereHas('examiners', function (Builder $query) use ($examDto): void {
                                    $query->whereIn('users.id', $examDto->examiners);
                                })
                                ->exists();
        
        if($hasConflictExaminers){
            throw new BusinessException("Один или несколько тестеров заняты в это время");
        }

        $examBeginTimeUtc= Carbon::parse(
                                            $examBeginTime->copy(),
                                            $user->organization->time_zone
                                        )->utc();
        $exam = DB::transaction(function () use ($examDto, $user ,$examBeginTime, $examEndTime, $examBeginTimeUtc,$examAddress) {
            $exam = Exam::create(
            [
                    'begin_time' => $examDto->beginTime,
                    'begin_time_utc' => $examBeginTimeUtc,
                    'address_id' => $examDto->addressId,
                    'capacity' => $examAddress->max_capacity,
                    'exam_type_id' => $examDto->examTypeId,
                    'comment' => $examDto->comment,
                    'creator_id'=> $user->id,
                    'end_time' => $examEndTime,
                    'organization_id' => $user->organization->id,
                    'date' => $examBeginTime->copy()->toDate()
                ]
            );
        
            $exam->examiners()->attach($examDto->examiners, ['organization_id'  => $user->organization->id]);
            return $exam;
        });        
        return $exam;
    }
}