<?php

namespace App\Listeners;


use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

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
        Log::channel('audit')->info('auth_logout', [
            'guard' => $event->guard,
            'user_type' => $event->user::class,
        ]);
    }
}
