<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamCode extends Model
{
    /** @use HasFactory<\Database\Factories\ExamCodeFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'exam_id',
        'student_id',
        'expired_at'
    ];

    public function exam(): BelongsTo{
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student(): BelongsTo{
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function isActual(){
        return $this->expired_at >= now();//Можно передать timezone, это будет самара
    }

    
}
