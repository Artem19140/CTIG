<?php

namespace App\Domain\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Hash;
use Log;

class CreateUserAction{
   public function execute(array $data, User $user){

    $superAdminRole = Role::where('name', UserRoles::SuperAdmin)->first();
    $orgAdminRole = Role::where('name', UserRoles::OrgAdmin)->first();

    if(in_array($superAdminRole->id, $data['roles'])){
        abort(403);
    }
    
    Log::info($data['roles']);
    Log::info($orgAdminRole->id . " oaбд");
    Log::info($user->isSuperAdmin() . " sa");

    if (
        \in_array($orgAdminRole->id, $data['roles'])
        &&
        !$user->isSuperAdmin()
    ) {
        abort(403);
    }

    $user = User::create([
        'email' => $data['email'],
        'name' => $data['name'],
        'surname' => $data['surname'],
        'patronymic' => $data['patronymic'],
        'job_title' => $data['jobTitle'],
        'password' => Hash::make($data['password']),
        'center_id' => $user->center_id
    ]);
    
    $user->roles()->sync($data['roles']);
   }
}