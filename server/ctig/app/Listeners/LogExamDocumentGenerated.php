<?php

namespace App\Listeners;

use App\Events\ExamDocumentGenerated;
use Log;

class LogExamDocumentGenerated
{

    public function __construct()
    {
        //
    }

    public function handle(ExamDocumentGenerated $event): void
    {
        Log::channel('business')->info('exam.documents_generated',[
            'exam_id' => $event->exam->id,
            'type' => $event->type->value
        ]);
    }
}
