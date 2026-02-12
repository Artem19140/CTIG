<?php

namespace App\Enums;

enum ExamAttemptStatus:string
{
    case Started = 'started';
    case Finished = 'finished';
    case Checked = 'checked';
    case Banned = 'banned';
}
