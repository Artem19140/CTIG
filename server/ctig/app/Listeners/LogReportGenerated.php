<?php

namespace App\Listeners;

use App\Events\ReportGenerated;
use Illuminate\Support\Facades\Log;

class LogReportGenerated
{
    public function __construct()
    {
        //
    }

    public function handle(ReportGenerated $event): void
    {
        Log::info('report_generated', [
            'type' => $event->type->value
        ]);
    }
}
