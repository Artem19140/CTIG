<?php

namespace App\Domain\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use App\Support\Log\BusinessLog;
use DB;
use Illuminate\Validation\ValidationException;

class UpdateUserAction{
   public function execute(array $data, User $updatedUser){
    $this->ensureHasNoRoleSuperAdmin($data);
    $this->ensureOrgAdminValidCreation($data, $updatedUser);
    DB::transaction(function() use($updatedUser, $data){
        $updatedUser->update($this->getAttributes($data));
        $updatedUser->roles()->sync($data['roles']);
        $this->log($updatedUser);
    });
    
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
        if(\in_array($superAdminRole->id, $data['roles'])){
            abort(403);
        }
    }

    protected function ensureOrgAdminValidCreation(array $data, User $user):void{
        $orgAdminRole = Role::findByEnum(UserRoles::OrgAdmin);
        if (
            \in_array($orgAdminRole->id, $data['roles'])
            &&
            !request()->user->isSuperAdmin()
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

    protected function log(User $updatedUser){
        BusinessLog::event('user_updated',[
            'updated_id' => $updatedUser->id
        ]);
    }

}