<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\EmployeeRole;
use App\Models\Scopes\BelongsToCenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable;
    use BelongsToCenter;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'job_title',
        'email',
        'password',
        'has_to_change_password',
        'is_active',
        'center_id',
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
        return $this->hasRole(EmployeeRole::SuperAdmin->value);
    }

    public function isActive(){
        return $this->is_active;
    }

    public function hasChangePassword(){
        return $this->has_to_change_password;
    }

    public function roles():BelongsToMany{
        return $this->belongsToMany(Role::class,'role_employee', 'employee_id', 'role_id');
    }

    public function hasAnyRole(EmployeeRole  ...$roles):bool{
        $roleValues = collect($roles)->map(fn (EmployeeRole $role) => $role->value);

        return $this->roles
            ->pluck('name') 
            ->map(fn ($role) => $role instanceof \BackedEnum ? $role->value : $role)
            ->intersect($roleValues)
            ->isNotEmpty();
    }
    
    public function hasRole(string $role):bool{
        return $this->roles->contains('name', $role);
    }

    public function exams(): BelongsToMany{
        return $this->belongsToMany(Exam::class, 'exam_examiner','examiner_id', 'exam_id');
    }

    public function center(): BelongsTo{
        return $this->belongsTo(Center::class,'center_id');
    }

    public function scopeActive(Builder $query){
        return $query->where('is_active', true);
    }

    protected function timeZone(): Attribute
    {
        return Attribute::get(function () {
            return $this->center->time_zone;
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(function () {
            return trim(
                $this->surname   . ' ' . $this->name . ' ' .$this->patronymic ?? ''
            );
        });
    }

    protected function fullNameShort(): Attribute
    {
        return Attribute::get(function () {
            return trim(
                ($this->surname  ?? '') . ' ' . (mb_strtoupper(mb_substr($this->name, 0, 1)).'.' ?? '') . ' ' .( mb_strtoupper(mb_substr($this->patronymic, 0, 1)).'.' ?? '')
            );
        });
    }

    public function resolveRedirect():string
    {
        return match (true){
            $this->hasRole(EmployeeRole::Operator->value) => route('foreign-nationals.index'),
            $this->hasRole(EmployeeRole::Scheduler->value) => route('exams.index'),
            $this->hasRole(EmployeeRole::Director->value) => route('foreign-nationals.index'),
            $this->hasRole(EmployeeRole::Examiner->value) => route('exams.index'),
            $this->hasRole(EmployeeRole::CenterAdmin->value) => route('centers.show', ['center' => $this->center]),
            $this->hasRole(EmployeeRole::SuperAdmin->value) => route('centers.show', ['center' => $this->center]),
            default => abort(403)
        };
    }
    
    
}
