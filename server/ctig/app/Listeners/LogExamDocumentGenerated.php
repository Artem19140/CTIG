<?php

namespace App\Listeners;

use App\Events\ExamDocumentGenerated;
use Illuminate\Support\Facades\Log;

class LogExamDocumentGenerated
{

    public function __construct()
    {
        //
    }

    public function handle(ExamDocumentGenerated $event): void
    {
        Log::info('document_generated', [
            'document' => $event->type->value,
            'exam_id' => $event->exam->id,
        ]);
    }
}
