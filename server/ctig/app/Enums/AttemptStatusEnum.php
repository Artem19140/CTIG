<?php

namespace App\Enums;

enum AttemptStatusEnum:string
{
    case Started = 'started';
    case Finished = 'finished';
    case Checked = 'checked';
    case Banned = 'banned';
}
