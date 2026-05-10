<?php

namespace App\Enums;

enum ExamDocuments:string
{
    case List = 'list'; 
    case Codes = 'codes';
    case Protocol = 'protocol';
    case Results = 'results';
}
