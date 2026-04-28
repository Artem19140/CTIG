<?php

namespace App\Domain\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Hash;


class CreateUserAction{
   public function execute(array $data, User $user){
   
    $this->ensureHasNoRoleSuperAdmin($data);
    $this->ensureOrgAdminValidCreation($data, $user);

    $user = User::create($this->getAttributes($data, $user));
    
    $user->roles()->sync($data['roles']);
   }


    protected function ensureHasNoRoleSuperAdmin(array $data):void{
        $superAdminRole = Role::findByEnum(UserRoles::SuperAdmin);
        if(in_array($superAdminRole->id, $data['roles'])){
            abort(403);
        }
    }

    protected function ensureOrgAdminValidCreation(array $data, User $user):void{
        $orgAdminRole = Role::findByEnum(UserRoles::OrgAdmin);
        if (
            \in_array($orgAdminRole->id, $data['roles'])
            &&
            !$user->isSuperAdmin()
        ) {
            abort(403);
        }
    }

    protected function getAttributes(array $data, User $user):array{
        return [
            'email' => $data['email'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'job_title' => $data['jobTitle'],
            'password' => Hash::make($data['password']),
            'center_id' => $user->center_id
        ];
    }

}