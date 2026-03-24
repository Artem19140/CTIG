<?php

use App\Enums\UserRoles;

return [
    UserRoles::Operator->value => 'Оператор',
    UserRoles::OrgAdmin->value => 'Администратор организации',
    UserRoles::Scheduler->value => 'Оператор',
    UserRoles::Director->value => 'Директор',
    UserRoles::Examiner->value => 'Экзаменатор',
];