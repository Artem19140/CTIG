<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'name' => UserRoles::class,
    ];

    public static function findByEnum(UserRoles $role){
        return self::where('name', $role)->firstOrFail();
    }

    public static  function getIdByEnum(UserRoles $role){
        return self::findByEnum($role)->id;
    }
}
