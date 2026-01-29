<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAddress extends Model
{
    /** @use HasFactory<\Database\Factories\ExamAddressFactory> */
    use HasFactory;
    protected $fillable = [
        'is_actual',
        'address'
    ];

    public function exams(): HasMany{
        return $this->hasMany(Exam::class, 'exam_address_id');
    }
}
