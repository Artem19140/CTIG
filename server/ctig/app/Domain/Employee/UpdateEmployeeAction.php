<?php

namespace App\Domain\Employee;

use App\Enums\EmployeeRole;
use App\Http\Resources\Employee\EmployeeResource;
use App\Models\Role;
use App\Models\Employee;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UpdateEmployeeAction{
   public function execute(array $data, Employee $employeeToUpdate){
    $this->ensureHasNoRoleSuperAdmin($data);
    $this->ensureOrgAdminValidCreation($data);
    $before = new EmployeeResource($employeeToUpdate)->resolve();
    DB::transaction(function() use($employeeToUpdate, $data){
        $employeeToUpdate->update($this->getAttributes($data));
        $employeeToUpdate->roles()->sync($data['roles']);
    });

    $this->log($employeeToUpdate, $before);
   }

   protected function ensureUniqueEmail(string $email, int $id){
    $emailNotUnique = Employee::where('email', $email)
        ->where('id', '<>', $id)
        ->exists();
    if($emailNotUnique){
        throw ValidationException::withMessages([
            'email' => 'Такой email уже занят'
        ]);
    }
   }


    protected function ensureHasNoRoleSuperAdmin(array $data):void{
        $superAdminRole = Role::findByEnum(EmployeeRole::SuperAdmin);
        if(\in_array($superAdminRole->id, $data['roles'])){
            abort(403);
        }
    }

    protected function ensureOrgAdminValidCreation(array $data):void{
        $orgAdminRole = Role::findByEnum(EmployeeRole::CenterAdmin);
        if (
            \in_array($orgAdminRole->id, $data['roles'])
            &&
            !request()->user()->isSuperAdmin()
        ) {
            abort(403);
        }
    }

    protected function getAttributes(array $data):array{
        return [
            'email' => $data['email'],
            'job_title' =>  $data['jobTitle'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
        ];
    }

    protected function log(Employee $updatedEmployee, array $before):void{
        Log::info('employee_updated', [
            'employee_updated_id' => $updatedEmployee->id,
            'changes' => [
                'before' => $before,
                'after' => new EmployeeResource($updatedEmployee)->resolve()
            ]
        ]);
    }
}