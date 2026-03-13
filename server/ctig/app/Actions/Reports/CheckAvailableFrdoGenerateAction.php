<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;

class CheckAvailableFrdoGenerateAction{
    public function execute(Carbon $examDate, bool $success): void{
        $notCheckedAttempts = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                                    ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                                    ->whereIn('status', AttemptStatus::unChecked())
                                    ->where('is_passed', $success)
                                    ->exists();
        $examDate = $examDate->format('d.m.Y');
        if($notCheckedAttempts){
            $name = $success ? 'сертификатов' : 'справок';
            throw new BusinessException("За $examDate не все попытки для $name проверены" );
        }
    }
}