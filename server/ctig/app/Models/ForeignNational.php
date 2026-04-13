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
        'citizenship',
        'phone',
        'creator_id',
        'photo',
        'passport_scan',
        'passport_translate_scan',
        'document_type',
        'gender',
        'comment',
        'surname_normalized',
        'name_normalized',
        'patronymic_normalized',
        'passport_number_normalized',
        'passport_series_normalized',
    ];

    protected $casts = [
        'exam_code_expired_at' => 'datetime',
        'date_birth' => 'date',
        'issued_date'=> 'date',
    ];

    
    
    public function exams(): BelongsToMany{
        return $this->belongsToMany(Exam::class, 'enrollments')->withPivot('reg_number', 'has_payment');
    }

    public function enrollments(){
        return $this->hasMany(Enrollment::class);
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
                ($this->surname  ?? '') . " " . ($this->name ?? '') . " " .  ($this->patronymic ?? '')
            );
        });
    }

    protected function fullNameShort(): Attribute
    {
        return Attribute::get(function () {
            return trim(
                ($this->surname  ?? '') . ' ' . (mb_strtoupper(mb_substr($this->name, 0, 1)).'.' ?? '') . ' ' .( mb_strtoupper(mb_substr($this->patronymic ?? '', 0, 1)))
            );
        });
    }

    protected function fullNameLatin(): Attribute
    {
        return Attribute::get(function () {
            return trim(
                ($this->surname_latin  ?? '') . " " . ($this->name_latin ?? '') . " " .  ($this->patronymic_latin ?? '')
            );
        });
    }


    protected function formattedPhone(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['phone'] ? $this->formatPhone($attributes['phone']) : null
        );
    }

    private function formatPhone(string $phone): string
    {
        if (str_starts_with($phone, '8')) {
            $phone = '7' . substr($phone, 1);
        }
        return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', '+$1 ($2) $3-$4-$5', $phone);
    }

}