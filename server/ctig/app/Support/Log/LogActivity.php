<?php

namespace App\Support\Log;

use App\Enums\Event;
use App\Enums\Resource;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class LogActivity{
    public static function event(Event $event, Resource | null $resource = null, array $context = []){
        ActivityLog::create([
            'actor_id' => context('actor_id'),
            'center_id' => context('center_id'),
            'actor_type' => context('actor_type'),
            'event' => $event,
            'resource' => $resource?->value,
            'context' => $context,
            'meta' => [
                'ip' => context('ip') ,
                'user_agent' => context('user_agent'),
            ]
        ]);

        $payload = [
            'actor_id' => context('actor_id'),
            'center_id' => context('center_id'),
            'actor_type' => context('actor_type'),
            'event' => $event->value,
            'resource' => $resource?->value,
            'context' => $context,
        ];

        Log::channel('events')->info('log_event', $payload);
    }
}