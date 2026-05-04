<?php 

namespace App\Enums;

use Log;

enum UserRoles :string {
    case Operator = 'operator';
    case Scheduler = 'scheduler';
    case Examiner = 'examiner';
    case VideoRecordOperator='video_record_operator';
    case Director = 'director';
    case OrgAdmin = 'org_admin';
    case SuperAdmin = 'super_admin';

    public static function implode(array $roles){
        
        $rolesValues = array_map(fn(UserRoles $role) => $role->value, $roles);
        return implode(',', $rolesValues);
    }
}