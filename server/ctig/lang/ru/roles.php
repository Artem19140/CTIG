<?php

use App\Enums\EmployeeRole;

return [
    EmployeeRole::Operator->value => 'Оператор',
    EmployeeRole::CenterAdmin->value => 'Администратор организации',
    EmployeeRole::Scheduler->value => 'Администратор экзаменов',
    EmployeeRole::Director->value => 'Директор',
    EmployeeRole::Examiner->value => 'Экзаменатор',
    EmployeeRole::VideoRecordOperator->value=>'Оператор видеозаписей'
];