<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentPolicy
{
    use BasePolicy;   
    public function view(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function payment(User $user, Enrollment $enrollment): bool
    {
        $this->sameCenter($user,$enrollment);
        if($user->hasAnyRole(
            UserRoles::Operator->value, 
            UserRoles::SuperAdmin->value
        )){
            return true;
        }
        if($user->hasRole(UserRoles::Examiner->value)){
            return $user->exams()->where('exams.id', $enrollment->exam_id)->exists();
        }
        return false;
    }

}
