<?php

namespace App\Domain\Exam\Rules;

use App\Exceptions\BusinessException;
use App\Http\Dto\ExamDto;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Actions\Exam\Manage\ValidateExaminersAction;

class ValidateExamCreationAction{
    public function __construct(
        protected ValidateExaminersAction $validateExaminers
    ){}
    public function execute(ExamDto $examDto, User $user, int | null $examId = null):int{
        $examType =  ExamType::find($examDto->examTypeId);
        $address = Address::find($examDto->addressId);
    
        if(!$address->is_active){
            throw new BusinessException('Адрес проведения экзамена неактуален');
        }

        if(!$examType->is_active){
            throw new BusinessException('Данный экзамен неактуален');
        }


        $examBeginTimeUtc= Carbon::parse(
                                        $examDto->beginTime->copy(),
                                        $user->center->time_zone
                                    )->utc();
                                    
        if($examBeginTimeUtc < Carbon::now()){
            throw new BusinessException('Экзамен нельзя создать на прошедшие даты');
        }

        if($examDto->capacity > $address->max_capacity){
            throw new BusinessException("Площадка вмещает максимум $address->max_capacity человек");
        }

        $this->validateExaminers->execute(
                                            $examDto->examiners, 
                                            $examDto->beginTime, 
                                            $examDto->beginTime->copy()->addMinutes($examType->duration),
                                            $examId
                                        );
        
        $hasConflictExam = Exam::where('is_cancelled', false)
                            ->where('begin_time', '<=', $examDto->beginTime->copy()->addMinutes($examType->duration)) //utc?!
                            ->where('end_time', '>=', $examDto->beginTime)
                            ->where('address_id', $address->id)
                            ->when($examId, function (Builder $query) use($examId){
                                $query->where('id', '<>', $examId);
                            })
                            ->exists(); 

        if($hasConflictExam){
            throw new BusinessException("В это время по данному адресу уже проводится экзамен");
        }
        return $examType->duration;
    }
}