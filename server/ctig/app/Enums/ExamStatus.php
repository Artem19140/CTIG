<?php

namespace App\Enums;

enum ExamStatus:string
{
    case Pending = 'pending';
    case Going = 'going';
    case Finished = 'finished';
    case Cancelled = 'cancelled';

    public function label():string{
        return match($this){
            self::Pending => 'Ожидается',
            self::Going => 'Идет',
            self::Finished => 'Завершен',
            self::Cancelled => 'Отменен',
        };
    }
}
