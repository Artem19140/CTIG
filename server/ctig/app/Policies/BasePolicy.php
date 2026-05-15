<?php

namespace App\Policies;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

trait BasePolicy
{
    public function sameCenter(Employee $employee, Model $model){
        if($employee->center_id === $model->center_id){
            return true;
        }
        return false;
    }
}
