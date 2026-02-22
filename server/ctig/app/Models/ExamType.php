<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function exams():HasMany{
        return $this->hasMany(Exam::class);
    }

    public function blocks():HasMany{
        return $this->hasMany( Block::class,"exam_type_id");
    }
}
