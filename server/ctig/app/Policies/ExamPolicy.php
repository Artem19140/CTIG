<?php

namespace App\Policies;

use App\Enums\EmployeeRole;
use App\Models\Exam;
use App\Models\Employee;


class ExamPolicy
{
    use BasePolicy;
    public function before(Employee $employee, string $ability): bool|null
    {
        if ($employee->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(Employee $employee): bool{
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Director, 
            EmployeeRole::Examiner, 
            EmployeeRole::Scheduler
        )){
            return true;
        }
        return false;
    }

    public function view(Employee $employee, Exam $exam): bool{
        if (!$this->sameCenter($employee, $exam)) {
            return false;
        }
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Director, 
            EmployeeRole::Scheduler
        )){
            return true;
        }
        return $this->examiner($employee, $exam);
    } 

    public function create(Employee $employee): bool{
        return $employee->hasAnyRole(EmployeeRole::Scheduler);
    }

    public function update(Employee $employee, Exam $exam): bool{
        if (!$this->sameCenter($employee, $exam)) {
            return false;
        }
        return $employee->hasAnyRole(EmployeeRole::Scheduler);
    }

    public function delete(Employee $employee, Exam $exam): bool{
        if (!$this->sameCenter($employee, $exam)) {
            return false;
        }
        return $employee->hasAnyRole(EmployeeRole::Scheduler);
    }

    public function frdo(Employee $employee): bool{
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Director
        )){
            return true;
        }
        return false;
    }

    public function list(Employee $employee, Exam $exam): bool{
        if (!$this->sameCenter($employee, $exam)) {
            return false;
        }
        if($employee->hasAnyRole(
            EmployeeRole::Operator, 
            EmployeeRole::Director
        )){
            return true;
        }
        return $this->examiner($employee, $exam);
    }

    public function examiner(Employee $employee, Exam $exam):bool{
        if (!$this->sameCenter($employee, $exam)) {
            return false;
        }
        if(!$employee->hasRole(EmployeeRole::Examiner->value)){
            return false;
        }
        return $employee->exams()
            ->wherePivot('exam_id', $exam->id)
            ->exists();
    }


}
