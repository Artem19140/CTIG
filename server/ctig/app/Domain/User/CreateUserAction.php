<?php

namespace App\Domain\User;

use App\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Hash;

class CreateUserAction{
   public function execute(array $data, User $user){

    $superAdminRole = Role::where('name', UserRoles::SuperAdmin)->first();

    if(\in_array($superAdminRole->id, $data['roles']) && !$user->isSuperAdmin()){
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