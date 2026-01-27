<?php

namespace App\Enums;

enum ExamResultStatus: string
{
    case Passed = 'Сдал';
    case Failed = 'Не сдал';
    case Absent = 'н/я';
    case Excluded = 'Снят';
}
