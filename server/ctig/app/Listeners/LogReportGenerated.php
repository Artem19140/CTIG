<?php

namespace App\Listeners;

use App\Events\ReportGenerated;
use App\Support\Log\BusinessLog;

class LogReportGenerated
{
    public function __construct()
    {
        //
    }

    public function handle(ReportGenerated $event): void
    {
        BusinessLog::event('report_generated', [
            'type' => $event->type->value
        ]);
    }
}
