<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'creator_id'
    ];

    protected $casts = [
        'is_actual' => 'boolean'
    ];
    
    public function exams():HasMany{
        return $this->hasMany(Exam::class);
    }

    public function isActual():bool{
        return $this->is_actual;
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function blocks():HasMany{
        return $this->hasMany( ExamBlock::class,"exam_type_id");
    }
}
