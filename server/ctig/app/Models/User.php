<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable;
    public function examsCreated():HasMany {
        return $this->hasMany(Exam::class, 'creator_id');
    }

    public function examTester(): BelongsToMany{
        return $this->belongsToMany(Exam::class, 'exam_tester','tester_id', 'exam_id');
    }

    public function documents(){
        return $this->belongsToMany(Document::class);
    }

    public function isAdmin(){
        return $this->is_admin;
    }

    public function organization(): BelongsTo{
        return $this->belongsTo(Organization::class,'organization_id');
    }
    
    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'job_title',
        'email',
        'password',
        'has_to_change_password',
        'is_work',
        'organization_id'
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
            'is_admin' => 'boolean',
            'is_work' => 'boolean'
        ];
    }
}
