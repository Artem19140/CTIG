<?php

namespace App\Enums;

enum ExamStatus: string
{
    case Pending = "Ожидается";
    case Checking = 'На проверке';
    case Completed = 'Завершен';
    case Cancelled = 'Отменен';
    case Started = 'Идет'; //Или как? Как только пошло начало, то сразу начат
}
