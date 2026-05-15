<?php

namespace App\Policies;

use App\Models\Attempt;
use App\Models\Employee;
use Illuminate\Auth\Access\Response;

class AttemptPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Employee $employee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Employee $employee, Attempt $attempt): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Employee $employee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $employee, Attempt $attempt): bool
    {
        return false;
    }

}
