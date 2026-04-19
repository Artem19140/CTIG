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
use Illuminate\Validation\ValidationException;

class ValidateExamForSave{
    public function __construct(
        protected ValidateExaminers $validateExaminers
    ){}
    public function execute(ExamDto $examDto, User $user, int | null $examId = null):int{
        $examType =  ExamType::find($examDto->examTypeId);
        $address = Address::find($examDto->addressId);

        if(!$address->is_active){
            throw ValidationException::withMessages([
                'addressId' => 'Адрес проведения экзамена не актуален'
            ]);
        }

        if(!$examType->is_active){
            throw ValidationException::withMessages([
                'examTypeId' => 'Адрес проведения экзамена не актуален'
            ]);
        }

        $beginTime = $examDto->beginTime;
        $examBeginTimeUtc= Carbon::parse(
                                        $beginTime,
                                        $user->center->time_zone
                                    )->utc();
                                    
        if($examBeginTimeUtc < Carbon::now()){
            throw ValidationException::withMessages([
                'date' => "Экзамен нельзя создать на прошедшие даты",
                'time' => "'Экзамен нельзя создать на прошедшие даты'"
            ]);
        }

        if($examDto->capacity > $address->max_capacity){
            throw ValidationException::withMessages([
                'capacity' => "Площадка вмещает максимум $address->max_capacity человек"
            ]);
        }
        $endTime = $beginTime->copy()->addMinutes($examType->duration);
        $this->validateExaminers->execute(
                                            $examDto->examiners, 
                                            $beginTime, 
                                            $endTime,
                                            $examId
                                        );
        
        $hasConflictExam = Exam::notCancelled()
                            ->whereBeginTimeLess($endTime)
                            ->whereEndTimeMore($beginTime)
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