<?php

namespace App\Domain\Exam\Validator;

use App\Domain\Center\CenterContext;
use App\Exceptions\BusinessException;
use App\Http\Dto\ExamDto;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ExamBeforeSaveValidator{
    public function __construct(
        protected ExaminersValidator $examinersValidator
    ){}
    public function execute(
        ExamDto $examDto, 
        int | null $examId = null
    ):int{
        $examType =  ExamType::findOrFail($examDto->examTypeId);
        
        $address = $this->findOrFailAddress($examDto->addressId);
        $this->ensureAddressIsActive($address);

        $beginTime = $examDto->beginTime;
        $endTime = $beginTime->copy()->addMinutes($examType->duration);

        $this->ensureBeginTimeNotPassed($beginTime);

        $this->ensureMinAllowedTimeNotPassed($beginTime, $examId);
        
        $this->ensureNotMoreCapacity($examDto->capacity, $address->max_capacity);

        $this->checkExamsConflicts(
            $beginTime, 
            $endTime,
            $address, 
            $examId
        );

        $this->examinersValidator->execute(
            $examDto->examiners, 
            $beginTime, 
            $endTime,
            $examId
        );

        return $examType->duration;
    }

    protected function findOrFailAddress(int $addressId):Address{
        $address =  Address::query()
            ->forCenter(app(CenterContext::class)->id())
            ->find($addressId);
        if(!$address){
            Log::warning('address not found, no same center', ['address_id' => $addressId]);
            abort(404);
        }
        return $address;
    }

    protected function ensureAddressIsActive(Address $address){
        if(!$address->is_active){
            throw ValidationException::withMessages([
                'addressId' => 'Адрес проведения экзамена не актуален'
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

    protected function ensureNotMoreCapacity(int $capacity, int $maxCapacity){
        if($capacity > $maxCapacity){
            throw ValidationException::withMessages([
                'capacity' => "Площадка вмещает максимум $maxCapacity человек"
            ]);
        }
    }

    protected function checkExamsConflicts(
        Carbon $beginTime, 
        Carbon $endTime, 
        Address $address, 
        int | null $examId
    ):void{
        $conflictExam = Exam::query()
            ->forCenter(app(CenterContext::class)->id())
            ->notCancelled()
            ->where('begin_time', '<=',$endTime)
            ->where('end_time', '>=',$beginTime)
            ->with(['type'])
            ->where('address_id', $address->id)
            ->when($examId, function (Builder $query) use($examId){
                $query->where('id', '<>', $examId);
            })
            ->first(); 

        if($conflictExam){
            $examConflictName = $conflictExam->short_name;
            $time = $conflictExam->begin_time->format('H:i');
            throw new BusinessException("В это время по данному адресу уже проводится экзамен по $examConflictName в $time");
        }
    }
}