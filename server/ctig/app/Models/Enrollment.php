<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'exam_code_expired_at'
    ];

    protected $casts = [
        'has_payment' => 'boolean',
        'exam_code_expired_at' => 'datetime'
    ];

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

    public static function for(Exam $exam, ForeignNational $foreignNational){
        return self::where('exam_id', $exam->id)
            ->where('foreign_national_id', $foreignNational->id);
    }

    public function changePaymentStatus():void{
        $this->has_payment = !$this->has_payment;
    }
}