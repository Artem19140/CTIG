<?php

namespace App\Domain\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateUserAction{
   public function execute(array $data, User $user){
   
    $this->ensureHasNoRoleSuperAdmin($data);
    $this->ensureOrgAdminValidCreation($data, $user);

    $user->update($this->getAttributes($data));
    $user->roles()->sync($data['roles']);
   }

   protected function ensureUniqueEmail(string $email, int $id){
    $emailNotUnique = User::where('email', $email)
        ->where('id', '<>', $id)
        ->exists();
    if($emailNotUnique){
        throw ValidationException::withMessages([
            'email' => 'Такой email уже занят'
        ]);
    }
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

    protected function getAttributes(array $data):array{
        return [
            'email' => $data['email'],
            'job_title' =>  $data['jobTitle'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
        ];
    }

}