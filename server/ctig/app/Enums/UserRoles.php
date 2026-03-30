<?php 

namespace App\Enums;

enum UserRoles :string {
    case Operator = 'operator';
    case Scheduler = 'scheduler';
    case Examiner = 'examiner';
    case VideoRecordOperator='video_record_operator';
    case Director = 'director';
    case OrgAdmin = 'org_admin';
    case SuperAdmin = 'super_admin';
}