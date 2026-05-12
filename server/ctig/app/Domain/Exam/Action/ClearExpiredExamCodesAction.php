<?php

namespace App\Domain\Exam\Action;

use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Enrollment;
use App\Support\Log\LogActivity;
use Carbon\Carbon;

class ClearExpiredExamCodesAction{
    public function execute():void{
        $count = Enrollment::where('exam_code_expired_at', '<', Carbon::now())
            ->whereNotNull('exam_code')
            ->update(['exam_code' => null]);

        LogActivity::event(
            event:Event::Updated,
            resource:Resource::Enrollment,
            context:[
                'actor' => 'cron',
                'deleted_exam_codes' => $count
            ]
        );
    }
}