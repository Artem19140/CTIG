<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Enums\Event;
use App\Support\Log\LogActivity;

class LogSuccessfullLogout
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
    public function handle(Logout $event): void
    {
        LogActivity::event(
            event:Event::Logout, 
            resource: null,
            context:[
                'guard' => $event->guard
            ]
        );
    }
}
