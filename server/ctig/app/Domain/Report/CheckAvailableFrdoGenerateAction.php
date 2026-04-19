<?php

namespace App\Domain\Report;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;

class CheckAvailableFrdoGenerateAction{
    public function execute(string $examDate){
        $examDate = Carbon::parse($examDate);
        $attemptsExists = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                            ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                            ->exists();
        $formattedDate = $examDate->copy()->format('d.m.Y');
        if(!$attemptsExists){
            throw new BusinessException("Попыток экзамена за $formattedDate нет");    
        }
        
        //можно тогда еще проверять, что для конкрентногго отчета есть или нет


        $uncheckedAttempts = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                    ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                    ->whereIn('status', AttemptStatus::unChecked())
                    ->exists();
        if($uncheckedAttempts){
            throw new BusinessException("Не все попытоки за $formattedDate проверены");  
        }
    }
}