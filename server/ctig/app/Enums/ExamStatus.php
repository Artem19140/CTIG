<?php

namespace App\Enums;

enum ExamStatus: string
{
    case Expected   = 'Expected';
    case Started   = 'started';
    case Finished  = 'finished';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
