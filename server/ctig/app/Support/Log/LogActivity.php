<?php

namespace App\Support\Log;

use App\Enums\Event;
use App\Enums\Resource;
use Illuminate\Support\Facades\Log;

class LogActivity{
    public static function event(Event $event, Resource | null $resource = null, array $context = []){

        $payload = [
            'actor_id' => auth()->user()->id,
            'actor_type' => auth()->user() ? auth()->user()->getMorphClass() : null,
            'event' => $event->value,
            'resource' => $resource->value,
            'context' => $context,
        ];

        Log::channel('events')->info('log_event', $payload);
    }
}