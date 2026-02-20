<?php

namespace App\Enums;

enum AttemptStatusEnum:string
{
    case Started = 'started'; //active
    case Finished = 'finished';
    case Checked = 'checked';
    case Banned = 'banned';

    public function canBeRated(): bool{
        return match($this){
            self::Banned => false,
            default => true
        };
    }

    public function canBeFinished(){
        return match($this){
            self::Started => true,
            default => false
        };
    }
}
