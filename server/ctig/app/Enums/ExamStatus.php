<?php

namespace App\Enums;

enum ExamStatus: string
{
    case Pending   = 'pending';
    case Checking  = 'checking';
    case Started   = 'started';
    case Completed = 'completed';
    case Cancelled = 'cancelled'; //Или как? Как только пошло начало, то сразу начат
}
