<?php 

namespace App\Enums;

enum UserRoles :string {
    case Operator = 'operator';
    case Scheduler = 'scheduler';
    case Examiner = 'examiner';
    case Director = 'director';
    case OrgAdmin = 'org_admin';
    case SuperAdmin = 'super_admin';
}