<?php

namespace App\Enums;

enum AttemptStatus:string
{
    case Started = 'Начата';
    case Finished = 'Закончена';
    case NeedCheck = 'Требует проверки';
    case Checked = 'Проверена';
    case Banned = 'Аннулирована';
}
