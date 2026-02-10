<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Student extends Model{

    use HasFactory, Notifiable;
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
        'issues_date',
        'address_reg',
        'migration_card_requisite',
        'citizenship',
        'phone',
        'creator_id',
        'exam_code',
        'exam_code_expired_at',
        'exam_id'
    ];
    
    public function exams(): BelongsToMany{
        return $this->belongsToMany(Exam::class);
    }
 
    public function attempts(): HasMany{
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    public function documents(): MorphMany{
        return $this->morphMany(Document::class, 'documentable');
    }
}



