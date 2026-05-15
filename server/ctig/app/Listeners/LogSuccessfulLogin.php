<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event): void
    {
        Log::info('login', [
            'guard' => $event->guard,
            'remember' => $event->remember,
        ]);
    }
}
