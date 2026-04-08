<?php

namespace App\Domain\User;

use App\Exceptions\BusinessException;
use App\Models\User;

class UserGuard{
    public function ensureUniqueEmail(string $email){
        $exists = User::where("email", $email)->exists();
        
        if($exists){
            throw new BusinessException("Сотрудник с таким email уже существует");
        }
    }
}