<?php

namespace App\Domain\Exam\Rules;

use App\Exceptions\BusinessException;
use App\Http\Dto\ExamDto;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ValidateExamForSave{
    public function __construct(
        protected ValidateExaminers $validateExaminers
    ){}
    public function execute(ExamDto $examDto, int | null $examId = null){
        $examType =  ExamType::find($examDto->examTypeId);
        $address = Address::find($examDto->addressId);

        $this->ensureAddressIsActive($address);

        $this->ensureExamTypeIsActive($examType);

        $beginTime = $examDto->beginTime;
                                    
        $this->ensureBeginTimeNotPassed($beginTime);

        $this->ensureMinAllowedTimeNotPassed($beginTime, $examId);
        
        $this->ensureNotFullCapacity($examDto->capacity, $address->max_capacity);

        $endTime = $beginTime->copy()->addMinutes($examType->duration);

        $this->validateExaminers->execute(
            $examDto->examiners, 
            $beginTime, 
            $endTime,
            $examId
        );

        $this->checkExamsConflicts(
            $beginTime, 
            $endTime, 
            $address, 
            $examId
        );

        return $examType->duration;
    }
    

    protected function ensureAddressIsActive(Address $address){
        if(!$address->is_active){
            throw ValidationException::withMessages([
                'addressId' => 'Адрес проведения экзамена не актуален'
            ]);
        }
    }

    protected function ensureExamTypeIsActive(ExamType $examType){
        if(!$examType->is_active){
            throw ValidationException::withMessages([
                'examTypeId' => 'Тип экзамена не актуален'
            ]);
        }
    }

    protected function ensureBeginTimeNotPassed(Carbon $beginTime){
        if($beginTime < Carbon::now()){
            throw ValidationException::withMessages([
                'date' => "Экзамен нельзя создать на прошедшие даты",
                'time' => "Экзамен нельзя создать на прошедшие даты"
            ]);
        }
    }

    protected function ensureMinAllowedTimeNotPassed(Carbon $beginTime, int | null $examId = null){
        if($examId){
            return ;
        }
        $minAllowedTime  = Carbon::now()->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS);
        if($beginTime < $minAllowedTime){
            $hours = Exam::CREATE_AVAILABLE_BEFORE_HOURS;
            throw ValidationException::withMessages([
                'time' => "Экзамен возможно создать минимум за $hours часа до его начала"
            ]);
        }
    }

    protected function ensureNotFullCapacity(int $capacity, int $maxCapacity){
        if($capacity > $maxCapacity){
            throw ValidationException::withMessages([
                'capacity' => "Площадка вмещает максимум $maxCapacity человек"
            ]);
        }
    }

    protected function checkExamsConflicts(Carbon $beginTime, Carbon $endTime, Address $address, int | null $examId){
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
            $examConflictName = $conflictExam->short_name ;
            $time = $conflictExam->begin_time->format('H:i');
            throw new BusinessException("В это время по данному адресу уже проводится экзамен по $examConflictName в $time");
        }
    }
}