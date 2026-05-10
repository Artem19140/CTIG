<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {
        Log::channel('audit')->info('auth_login', [
            'guard' => $event->guard,
            'user_id' => $event->user->getAuthIdentifier(),
            'user_type' => $event->user::class,
            'remember' => $event->remember,
        ]);
    }
}
