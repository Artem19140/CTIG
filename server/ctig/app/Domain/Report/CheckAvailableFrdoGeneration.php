<?php

namespace App\Domain\Report;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;

class CheckAvailableFrdoGeneration{
    public function execute(string $examDate, bool $success):void{
        $examDate = Carbon::parse($examDate);
        $attemptsExists = Attempt::whereCreatedAtMore($examDate->copy()->startOfDay())
                            ->whereCreatedAtLess($examDate->copy()->endOfDay())
                            ->exists();

        $formattedDate = $examDate->copy()->format('d.m.Y');

        if(!$attemptsExists){
            throw new BusinessException("Попыток экзамена за $formattedDate нет");    
        }


        $uncheckedAttempts = Attempt::whereCreatedAtMore($examDate->copy()->startOfDay())
                    ->whereCreatedAtLess($examDate->copy()->endOfDay())
                    ->whereIn('status', AttemptStatus::unChecked())
                    ->exists();

        if($uncheckedAttempts){
            throw new BusinessException("Не все попытки за $formattedDate проверены");  
        }

        $attemptsForReportExists = Attempt::with(['exam.type','foreignNational','exam.address'])
                    ->whereCreatedAtMore($examDate->copy()->startOfDay())
                    ->whereCreatedAtLess($examDate->copy()->endOfDay())
                    ->where('is_passed',$success)
                    ->where('status', AttemptStatus::Checked)
                    ->exists();

        if(!$attemptsForReportExists){
            $reportName = $success ? 'сертификатов' : 'справок';
            $date = $examDate->format('d.m.Y');
            throw new BusinessException("Данных для $reportName за $date нет");
        }
    }
}