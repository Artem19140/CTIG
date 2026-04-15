<?php

namespace App\Enums;

enum ExamResultStatus:string
{
    case Banned = 'banned'; 
    case Absent = 'absent';
    case Failed = 'failed';
    case Passed = 'passed';
}
