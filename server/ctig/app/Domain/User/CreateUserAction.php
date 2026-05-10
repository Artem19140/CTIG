<?php

namespace App\Domain\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use App\Support\Log\BusinessLog;
use DB;
use Hash;


class CreateUserAction{
   public function execute(array $data, User $creator){
        $this->ensureHasNoRoleSuperAdmin($data);
        $this->ensureOrgAdminValidCreation($data, $creator);
        
        DB::transaction(function() use($creator, $data){
            $user = User::create($this->getAttributes($data, $creator));
            $user->roles()->sync($data['roles']);
            $this->log($creator, $user);
        });
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

    protected function log(User $user, User $createdUser){
        BusinessLog::event('user_created',[
            'creator_id' => $user->id,
            'created_id' => $createdUser->id
        ]);
    }

}