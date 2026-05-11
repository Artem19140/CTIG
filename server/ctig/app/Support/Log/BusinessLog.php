<?php

namespace App\Support\Log;

use Illuminate\Support\Facades\Log;

class BusinessLog{
    public static function event(string $event, array $data = []){
        Log::channel('business')->info($event, $data);
    }

    public static function error(string $event, array $data = []){
        Log::channel('business')->error($event, $data);
    }
}