<?php

namespace App\Support\Log;

use App\Enums\Event;
use Illuminate\Support\Facades\Log;

class AuditLog{
    public static function event(Event $event, array $data = []){
        Log::channel('audit')->info($event->value, $data);
    }

    public static function error(string $event, array $data = []){
        Log::channel('audit')->error($event, $data);
    }
}