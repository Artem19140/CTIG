<?php

namespace App\Domain\Exam\Validator;

use App\Domain\Center\CenterContext;
use App\Exceptions\BusinessException;
use App\Models\Address;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class ExamsConflictValidator{
    public function validate(){

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