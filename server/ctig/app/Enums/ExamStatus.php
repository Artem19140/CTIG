<?php

namespace App\Enums;

enum ExamStatus:string
{
    case Pending = 'pending';
    case Going = 'going'; //active
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label():string{
        return match($this){
            self::Pending => 'Ожидается',
            self::Going => 'Идет',
            self::Completed => 'Завершен',
            self::Cancelled => 'Отменен',
        };
    }
}
