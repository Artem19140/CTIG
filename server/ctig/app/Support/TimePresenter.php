<?php

namespace App\Support;

use App\Models\Center;
use Carbon\Carbon;

class TimePresenter
{
    /**
     * Create a new class instance.
     */
    public static function forCenter(Carbon | null $date, Center $center){
        if(!$date){
            return ;
        }
        return $date->copy()->setTimezone($center->time_zone);
    }
}
