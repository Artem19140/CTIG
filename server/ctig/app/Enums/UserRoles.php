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

    public static function implode(array $roles){
        
        $rolesValues = array_map(fn(UserRoles $role) => $role->value, $roles);
        return implode(',', $rolesValues);
    }

    public static function except(self ...$roles): array{
        return array_filter(
            self::cases(),
            fn (self $role) => ! in_array($role, $roles, true)
        );
    }
}