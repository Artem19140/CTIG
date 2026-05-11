<?php

namespace App\Enums;

enum Resource:string
{
    case ForeignNational = 'foreign_national';
    case Exam = 'exam';
    case Enrollment = 'enrollment';
    case Report = 'report';
    case Center = 'center';
    case File = 'file';
    case User = 'user';
    case Address = 'address';
    case Attempt = 'attempt';
    
    case AttemptAnswer = 'attempt_answer';
    case Violation = 'violation';
}
