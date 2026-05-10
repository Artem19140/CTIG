<?php

namespace App\Listeners;

use App\Events\ExamDocumentGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class LogExamDocumentGenerated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExamDocumentGenerated $event): void
    {
        Log::channel('business')->info('exam.documents_generated',[
            'exam_id' => $event->exam->id,
            'user_id' => $event->user->id,
            'type' => $event->type->value
        ]);
    }
}
