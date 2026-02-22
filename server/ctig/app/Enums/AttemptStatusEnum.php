<?php

namespace App\Enums;

enum AttemptStatusEnum:string
{
    case Active = 'active'; //active
    case Finished = 'finished';
    case Checked = 'checked';
    case Banned = 'banned';

    public function canBeRated(): bool{
        return match($this){
            self::Banned => false,
            default => true
        };
    }

    public function canBeFinished(): bool{
        return match($this){
            self::Active => true,
            default => false
        };
    }

    public static function  unChecked(): array{
        return [
            self::Active,
            self::Finished
        ];
    }
}
