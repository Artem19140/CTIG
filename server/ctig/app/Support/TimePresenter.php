<?php

namespace App\Support;

use App\Models\Center;
use Carbon\Carbon;

class TimePresenter
{
    /**
     * Create a new class instance.
     */
    public static function forCenter(Carbon $date, Center $center){
        return $date->copy()->setTimezone($center->time_zone);
    }
}
