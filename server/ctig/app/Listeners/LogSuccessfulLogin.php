<?php

namespace App\Listeners;

use App\Enums\Event;
use App\Support\Log\LogActivity;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event): void
    {
        LogActivity::event(
            event: Event::Login,
            resource:null,
            context:[
                'guard' => $event->guard,
                'user_id' => $event->user->getAuthIdentifier(),
                'user_type' => $event->user::class,
                'remember' => $event->remember,
            ]
        );
    }
}
