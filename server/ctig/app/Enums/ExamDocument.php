<?php

namespace App\Enums;

enum ExamDocument:string
{
    case List = 'list'; 
    case Codes = 'codes';
    case Protocol = 'protocol';
    case Results = 'results';
}
