<?php

namespace App\Domain\Report;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;

class CheckAvailableFrdoGeneration{
    public function execute(string $examDate, bool $success):void{
        $examDate = Carbon::parse($examDate);
        $this->ensureAttemptsExists($examDate);
        $this->ensureAllAttemptsChecked($examDate);
        $this->ensureHasDataForReportType($examDate, $success);
    }

    protected function ensureAttemptsExists(Carbon $examDate):void{
        $attemptsExists = Attempt::whereCreatedAtMore($examDate->copy()->startOfDay())
            ->whereCreatedAtLess($examDate->copy()->endOfDay())
            ->exists();

        if(!$attemptsExists){
            $formattedDate = $examDate->copy()->format('d.m.Y');
            throw new BusinessException("Попыток экзамена за $formattedDate нет");    
        }

    }
    protected function ensureAllAttemptsChecked(Carbon $examDate):void{
        $uncheckedAttempts = Attempt::whereCreatedAtMore($examDate->copy()->startOfDay())
            ->whereCreatedAtLess($examDate->copy()->endOfDay())
            ->whereIn('status', AttemptStatus::unChecked())
            ->exists();

        if($uncheckedAttempts){
            $formattedDate = $examDate->copy()->format('d.m.Y');
            throw new BusinessException("Не все попытки за $formattedDate проверены");  
        }

    }

    protected function ensureHasDataForReportType (Carbon $examDate, bool $success):void{
        $attemptsForReportExists = Attempt::whereCreatedAtMore($examDate->copy()->startOfDay())
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