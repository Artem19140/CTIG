<?php

namespace App\Listeners;

use App\Enums\Event;
use App\Enums\Resource;
use App\Events\ReportGenerated;
use App\Support\Log\LogActivity;

class LogReportGenerated
{
    public function __construct()
    {
        //
    }

    public function handle(ReportGenerated $event): void
    {
        LogActivity::event(
            event:Event::Access, 
            resource:Resource::Report,
            context:[
                'type' => $event->type->value
            ]
        );
    }
}
