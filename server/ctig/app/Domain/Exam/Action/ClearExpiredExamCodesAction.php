<?php

namespace App\Domain\Exam\Action;

use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClearExpiredExamCodesAction{
    public function execute():void
    {
        context(['actor_type' => 'cron', 'actor_id' => null]);
        $count = Enrollment::where('exam_code_expired_at', '<', Carbon::now())
            ->whereNotNull('exam_code')
            ->update(['exam_code' => null]);
        if($count > 0){
            Log::info('deleted_exam_codes', [
                'count' => $count,
                'actor' => 'cron'
            ]);
        }
    }
}