<?php

namespace App\Listeners;

use App\Enums\Event;
use App\Enums\Resource;
use App\Events\ExamDocumentGenerated;
use App\Support\Log\LogActivity;

class LogExamDocumentGenerated
{

    public function __construct()
    {
        //
    }

    public function handle(ExamDocumentGenerated $event): void
    {
        LogActivity::event(
            event: Event::Generated,
            resource: Resource::Exam,
            context:[
                'exam_id' => $event->exam->id,
                'document' => $event->type->value
            ]);
    }
}
