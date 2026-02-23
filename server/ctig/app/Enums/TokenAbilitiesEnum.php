<?php

namespace App\Enums;

enum TokenAbilitiesEnum :string{
    case SYSTEM_ACCESS = 'system-access';
    case CHANGE_PASSWORD = 'change-password';
    case EXAM_PREPARE = 'exam-prepare';
    case EXAM_ACCESS = 'exam-access';
}