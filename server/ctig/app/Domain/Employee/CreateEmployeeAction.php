<?php

namespace App\Domain\Employee;

use App\Enums\EmployeeRole;
use App\Models\Role;
use App\Models\Employee;
use DB;
use Hash;
use Illuminate\Support\Facades\Log;


class CreateEmployeeAction{
   public function execute(array $data, Employee $creator){
        $this->ensureHasNoRoleSuperAdmin($data);
        $this->ensureCenterAdminValidCreation($data, $creator);
        
        DB::transaction(function() use($creator, $data){
            $employee = Employee::create($this->getAttributes($data, $creator));
            $employee->roles()->sync($data['roles']);
            $this->log($creator);
        });
   }

    protected function ensureHasNoRoleSuperAdmin(array $data):void{
        $superAdminRole = Role::findByEnum(EmployeeRole::SuperAdmin);
        if(\in_array($superAdminRole->id, $data['roles'])){
            abort(403);
        }
    }

    protected function ensureCenterAdminValidCreation(array $data, Employee $creator):void{
        $centerAdminRole = Role::findByEnum(EmployeeRole::CenterAdmin);
        if (
            \in_array($centerAdminRole->id, $data['roles'])
            &&
            !$creator->isSuperAdmin()
        ) {
            abort(403);
        }
    }

    protected function getAttributes(array $data, Employee $employee):array{
        return [
            'email' => $data['email'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'job_title' => $data['jobTitle'],
            'password' => Hash::make($data['password']),
            'center_id' => $employee->center_id
        ];
    }

    protected function log(Employee $createdEmployee):void{
        Log::info('employee_created', [
            'employee_created_id' => $createdEmployee->id
        ]);
    }

}