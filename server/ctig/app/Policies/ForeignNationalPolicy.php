<?php

namespace App\Policies;

use App\Enums\EmployeeRole;
use App\Models\ForeignNational;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class ForeignNationalPolicy
{
    use BasePolicy;
    public function view(Employee $employee, ForeignNational $foreignNational): bool
    {   
        if (!$this->sameCenter($employee, $foreignNational)) {
            return false;
        }
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Director
        )){
            return true;
        }
        if($employee->hasRole(EmployeeRole::Examiner->value)){
            return $employee->exams()->whereHas('foreignNationals', function (Builder $query) use($foreignNational){
                $query->where('foreign_national_id', $foreignNational->id);
            })->exists();
        }
        return false;
    }

    public function viewAny(Employee $employee): bool{
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Director
        )){
            return true;
        }
        return false;
    }

    public function create(Employee $employee): bool{
        return $employee->hasAnyRole(EmployeeRole::Operator);
    }

    public function update(Employee $employee, ForeignNational $foreignNational): bool{
        if (!$this->sameCenter($employee, $foreignNational)) {
            return false;
        }
        return $employee->hasAnyRole(EmployeeRole::Operator);
    }

    public function export(Employee $employee): bool{
        return $employee->hasAnyRole(EmployeeRole::Director);
    }
    
    public function files(Employee $employee):bool{
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Examiner
        )){
            return true;
        }
        return false;
    }

}
