<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable {

    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable=[
        'surname',
        'name',
        'patronymic',
        'date_birth',
        'surname_latin',
        'name_latin',
        'patronymic_latin',
        'passport_number',
        'passport_series',
        'issued_by',
        'issued_date',
        'address_reg',
        'migration_card_requisite',
        'citizenship',
        'phone',
        'creator_id',
        'exam_code',
        'exam_code_expired_at',
        'exam_id',
        'photo_path',
        'passport_scan_path',
        'document_type',
        'sex'
    ];

    protected $casts = [
        'exam_code_expired_at' => 'datetime',
        'date_birth' => 'date',
        'issued_date'=> 'date',
    ];
    
    public function exams(): BelongsToMany{
        return $this->belongsToMany(Exam::class)->withPivot('reg_number');
    }
 
    public function attempts(): HasMany{
        return $this->hasMany(Attempt::class, 'student_id');
    }

    public function documents(): MorphMany{
        return $this->morphMany(Document::class, 'documentable');
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

}



