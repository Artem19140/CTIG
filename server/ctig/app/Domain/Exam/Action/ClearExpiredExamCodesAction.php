<?php

namespace App\Domain\Exam\Action;

use App\Models\Enrollment;
use Carbon\Carbon;

class ClearExpiredExamCodesAction{
    public function execute():void{
        Enrollment::where('exam_code_expired_at', '<', Carbon::now())
            ->whereNotNull('exam_code')
            ->update(['exam_code' => null]);
    }
}