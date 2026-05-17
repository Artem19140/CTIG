<?php

namespace App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Employee;

class BanAttemptAction{
    public function execute(Attempt $attempt, string $banReason, Employee $employee):void{
        $this->ensureNotBanned($attempt);

        if(!$attempt->finished_at){
            $attempt->finished_at = $attempt->last_activity_at;
        }

        $attempt->ban_reason = $banReason;
        $attempt->ban_by_id = $employee->id;

        $attempt->ban();

        $attempt->save();
    }

    protected function ensureNotBanned(Attempt $attempt){
        if($attempt->isBanned()){
            throw new BusinessException('Попытка аннулирована');
        }
    }
}