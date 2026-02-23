<?php

namespace App\Enums;

enum TokenAbilities :string{
    case SystemAccess = 'system-access';
    case ChangePassword = 'change-password';
    case ExamPrepare = 'exam-prepare';
    case ExamAccess = 'exam-access';
}