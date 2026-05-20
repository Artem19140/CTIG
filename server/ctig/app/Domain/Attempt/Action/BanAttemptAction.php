<?php

namespace App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class BanAttemptAction{
    public function __construct(
        protected FinishAttemptAction $finishAttemptAction
    ){}

    public function execute(
        Attempt $attempt, 
        string $banReason, 
        Employee $employee
    ):void{
        DB::transaction(function()use($attempt, $banReason, $employee){

            $this->ensureNotBanned($attempt);

            $this->finishAndIfNeededFinilize($attempt);

            $attempt->ban_reason = $banReason;
            $attempt->ban_by_id = $employee->id;
            $attempt->ban();
            $attempt->save();
        });
    }

    protected function ensureNotBanned(Attempt $attempt):void{
        if($attempt->isBanned()){
            throw new BusinessException('Попытка аннулирована');
        }
    }

    protected function finishAndIfNeededFinilize(Attempt $attempt):void{
        if($attempt->isFinished()){
            return;
        }
        $this->finishAttemptAction->execute($attempt);
    }
}