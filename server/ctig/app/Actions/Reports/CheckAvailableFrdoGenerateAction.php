<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;

class CheckAvailableFrdoGenerateAction{
    public function execute(string $examDate): bool{
        $examDate = Carbon::parse($examDate);
        $attemptsExists = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                            ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                            ->exists();
        if(!$attemptsExists){
            return false;    
        }

        $uncheckedAttempts = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                ->whereIn('status', AttemptStatus::unChecked())
                ->exists();
        if(!$uncheckedAttempts){
            return false;
        }
        return true;
    }
}