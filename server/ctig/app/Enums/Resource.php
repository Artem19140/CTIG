<?php

namespace App\Enums;

enum Resource:string
{
    case ForeignNational = 'foreign_national';
    case Exam = 'exam';
    case Enrollment = 'enrollment';
    case Report = 'report';
    case File = 'file';
    case User = 'user';
    case Attempt = 'attempt';
    case AttemptAnswer = 'attempt_answer';
    case Violation = 'violation';
}
