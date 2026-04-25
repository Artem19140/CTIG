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
    public function execute(ExamDto $examDto, User $user, int | null $examId = null){
        $examType =  ExamType::find($examDto->examTypeId);
        $address = Address::find($examDto->addressId);

        if(!$address->is_active){
            throw ValidationException::withMessages([
                'addressId' => 'Адрес проведения экзамена не актуален'
            ]);
        }

        if(!$examType->is_active){
            throw ValidationException::withMessages([
                'examTypeId' => 'Тип экзамена не актуален'
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
                'time' => "Экзамен нельзя создать на прошедшие даты"
            ]);
        }

        if($examBeginTimeUtc < Carbon::now()->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS)){
            $hours = Exam::CREATE_AVAILABLE_BEFORE_HOURS;
            throw ValidationException::withMessages([
                'time' => "Экзамен возможно создать минимум за $hours часа до его начала"
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
        
        $conflictExam = Exam::notCancelled()
                            ->whereBeginTimeLess($endTime)
                            ->whereEndTimeMore($beginTime)
                            ->with(['type'])
                            ->where('address_id', $address->id)
                            ->when($examId, function (Builder $query) use($examId){
                                $query->where('id', '<>', $examId);
                            })
                            ->first(); 

        if($conflictExam){
            $examConflictName = $conflictExam->short_name. " в ". $conflictExam->begin_time->format('H:i');
            throw new BusinessException("В это время по данному адресу уже проводится экзамен по $examConflictName");
        }
        return $examType->duration;
    }
}