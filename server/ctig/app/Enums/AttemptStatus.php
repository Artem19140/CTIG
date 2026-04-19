<?php

namespace App\Enums;

enum AttemptStatus:string
{
    case Pending = 'pending';
    case Active = 'active'; //active
    case Finished = 'finished';
    case Checked = 'checked';
    case Banned = 'banned';

    public function canBeRated(): bool{
        return match($this){
            self::Banned, self::Pending => false,
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
            self::Pending,
            self::Active,
            self::Finished
        ];
    }
}
