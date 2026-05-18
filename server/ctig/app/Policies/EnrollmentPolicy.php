<?php

namespace App\Policies;

use App\Enums\EmployeeRole;
use App\Models\Enrollment;
use App\Models\Employee;

class EnrollmentPolicy
{
    use BasePolicy;   

    public function viewAny(Employee $employee): bool
    {
        if($employee->hasAnyRole(
            EmployeeRole::Operator,
            EmployeeRole::Director,
            EmployeeRole::Examiner
        )){
            return true;
        }
        return false;
    }
    public function view(Employee $employee, Enrollment $enrollment): bool
    {
        return false;
    }

    public function create(Employee $employee): bool
    {
        return $employee->hasAnyRole(EmployeeRole::Operator);
    }

    public function payment(Employee $employee, Enrollment $enrollment): bool
    {
        if (!$this->sameCenter($employee, $enrollment)) {
            return false;
        }
        
        if($employee->hasAnyRole(
            EmployeeRole::Operator
        )){
            return true;
        }
        return $employee->can('examiner', $enrollment->exam);
        
    }

    public function statement(Employee $employee, Enrollment $enrollment): bool
    {

        if (!$this->sameCenter($employee, $enrollment)) {
            return false;
        }
        
        return $employee->hasAnyRole(EmployeeRole::Operator);
    }

}
