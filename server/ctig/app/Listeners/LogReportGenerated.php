<?php

namespace App\Listeners;

use App\Events\ReportGenerated;
use App\Support\Log\BusinessLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class LogReportGenerated
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
    public function handle(ReportGenerated $event): void
    {
        BusinessLog::event('report_generated', [
            'user_id' => $event->user->id,
            'type' => $event->type->value
        ]);
    }
}
