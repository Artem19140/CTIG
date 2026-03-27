<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ForeignNational extends Authenticatable {

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
        'passport_translate_scan',
        'document_type',
        'gender'
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
        return $this->hasMany(Attempt::class, 'foreign_national_id');
    }

    public function documents(): MorphMany{
        return $this->morphMany(Document::class, 'documentable');
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected function fullPassport(): Attribute
    {
        return Attribute::get(function () {
            return trim(
                ($this->passport_series ?? '') . ' ' . ($this->passport_number ?? '')
            );
        });
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



