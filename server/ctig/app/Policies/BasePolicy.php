<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait BasePolicy
{
    public function sameCenter(User $user, Model $model){
        if($user->isSuperAdmin()){
            return ;
        }
        if($user->center_id !== $model->center_id){
            abort(403);
        }
    }
}
