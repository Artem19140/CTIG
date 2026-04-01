<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'job_title',
        'email',
        'password',
        'has_to_change_password',
        'is_active',
        'organization_id',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean'
        ];
    }

    public function isSuperAdmin(){
        return $this->hasRole(UserRoles::SuperAdmin->value);
    }

    public function roles(){
        return $this->belongsToMany(Role::class,'role_user', 'user_id', 'role_id');
    }
    
    public function hasRole(string $role){
        return $this->roles()->where('name', $role)->exists();
    }

    public function exams(): BelongsToMany{
        return $this->belongsToMany(Exam::class, 'exam_examiner','examiner_id', 'exam_id');
    }

    public function organization(): BelongsTo{
        return $this->belongsTo(Organization::class,'organization_id');
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(function () {
            return trim(
                ($this->surname  ?? '') . ' ' . (mb_strtoupper(mb_substr($this->name, 0, 1)).'.' ?? '') . ' ' .( mb_strtoupper(mb_substr($this->patronymic, 0, 1)).'.' ?? '')
            );
        });
    }
    
    
}
