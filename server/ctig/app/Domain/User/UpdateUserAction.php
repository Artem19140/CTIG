<?php

namespace App\Domain\User;

use App\Enums\Event;
use App\Enums\Resource;
use App\Enums\UserRoles;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Support\Log\LogActivity;
use DB;
use Illuminate\Validation\ValidationException;

class UpdateUserAction{
   public function execute(array $data, User $userToUpdate){
    $this->ensureHasNoRoleSuperAdmin($data);
    $this->ensureOrgAdminValidCreation($data, $userToUpdate);
    $before = new UserResource($userToUpdate)->resolve();
    DB::transaction(function() use($userToUpdate, $data){
        $userToUpdate->update($this->getAttributes($data));
        $userToUpdate->roles()->sync($data['roles']);
    });

    $this->log($userToUpdate, $before);
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

    protected function log(User $updatedUser, array $before){
        LogActivity::event(
            event:Event::Updated,
            resource:Resource::User,
            context:[
                'user_updated_id' => $updatedUser->id,
                'changes' => [
                    'before' => $before,
                    'after' => new UserResource($updatedUser)->resolve()
                ]
            ]
        );
    }
}