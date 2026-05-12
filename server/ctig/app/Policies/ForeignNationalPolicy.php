<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\ForeignNational;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class ForeignNationalPolicy
{
    use BasePolicy;
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ForeignNational $foreignNational): bool
    {
        //$this->sameCenter($user, $foreignNational);
        if($user->hasAnyRole(
            UserRoles::Operator->value, 
            UserRoles::Director->value, 
            UserRoles::SuperAdmin->value
        )){
            return true;
        }
        if($user->hasRole(UserRoles::Examiner->value)){
            return $user->exams()->whereHas('foreignNationals', function (Builder $query) use($foreignNational){
                $query->where('foreign_national_id', $foreignNational->id);
            })->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ForeignNational $foreignNational): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ForeignNational $foreignNational): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ForeignNational $foreignNational): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ForeignNational $foreignNational): bool
    {
        return false;
    }
}
