<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_id',
        'foreign_national_id',
        'has_payment',
        'reg_number',
        'status',
        'creator_id',
        'center_id',
        'exam_code',
        'exam_code_expired_at',
        'cancelled_at',
        'rescheduled_at',
        'exam_code_used_at'
    ];
    protected $casts = [
        'has_payment' => 'boolean',
        'exam_code_expired_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'exam_code_used_at' => 'datetime',
    ];

    public function isRescheduled():bool{
        return $this->rescheduled_at !== null;
    }

    public function isCancelled():bool{
        return $this->cancelled_at !== null;
    }

    public function hasPayment():bool{
        return $this->has_payment;
    }

    public function exam():BelongsTo{
        return $this->belongsTo(Exam::class);
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function center():BelongsTo{
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function foreignNational():BelongsTo{
        return $this->belongsTo(ForeignNational::class);
    }

    public function attempt():HasOne{
        return $this->hasOne(Attempt::class, 'enrollment_id');
    }


    public static function for(Exam $exam, ForeignNational $foreignNational){
        return self::where('exam_id', $exam->id)
            ->where('foreign_national_id', $foreignNational->id);
    }

    public function changePaymentStatus():void{
        $this->has_payment = !$this->has_payment;
    }

    protected function timeZone(): Attribute
    {
        return Attribute::get(function () {
            return $this->center->time_zone;
        });
    }
}