<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function payment(User $user, Enrollment $enrollment): bool
    {
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

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }
}
