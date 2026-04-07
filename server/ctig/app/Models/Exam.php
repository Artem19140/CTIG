<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'begin_time',
        'begin_time_utc',
        'exam_type_id',
        'creator_id',
        'session_number',
        'capacity',
        'comment',
        'group',
        'address_id',
        'date',
        'cancelled_reason',
        'is_cancelled',
        'center_id',
        'end_time',
        'protocol_comment',
        'begin_time_real',
        'end_time_real'
    ];

    protected $casts = [
        'end_time' => 'datetime',
        'begin_time' => 'datetime',
        'begin_time_utc' => 'datetime',
        'date'=>'date'
    ];

    public function examType(): BelongsTo{
        return $this->belongsTo(ExamType::class);
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function examiners():BelongsToMany{
        return $this->belongsToMany(User::class, 'exam_examiner','exam_id', 'examiner_id');
    }

    public function foreignNationals():BelongsToMany{
        return $this->belongsToMany(ForeignNational::class)->withPivot('reg_number', 'has_payment');
    }

    public function attempts(): HasMany{
        return $this->hasMany(Attempt::class, 'exam_id');
    }

    public function documents(): MorphMany{
        return $this->morphMany(Document::class, 'documentable');
    }

    public function address(): BelongsTo{
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function isCompleted(){
        return $this->begin_time_utc->addMinutes($this->examType->duration)->isPast();
    }

    public function isGoing(){
        return !$this->begin_time_utc->addMinutes($this->examType->duration)->isPast() && $this->begin_time_utc->isPast();
    }

    public function isCancelled(){
        return $this->is_cancelled;
    }

    public function center(): BelongsTo{
        return $this->belongsTo(Center::class,'center_id');
    }

    protected function duration(): Attribute
    {
        return Attribute::get(function () {
            return $this->examType->duration;
        });
    }

    protected function addressName(): Attribute
    {
        return Attribute::get(function () {
            return $this->address->address;
        });
    }

}
