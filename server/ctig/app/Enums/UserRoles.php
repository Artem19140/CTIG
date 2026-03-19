<?php 

namespace App\Enums;

enum UserRoles :string {
    case Operator = 'operator';
    case Scheduler = 'scheduler';
    case EXAMINER = 'examiner';
    case Director = 'director';
    case OrgAdmin = 'org_admin';
}