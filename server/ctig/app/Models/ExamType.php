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
        'is_active',
        'has_speaking_tasks',
        'tasks_count',
        'need_human_check',
        'min_mark',
        'protocol_name'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_speaking_tasks' => 'boolean',
        'need_human_check'  => 'boolean'
    ];
    
    public function exams():HasMany{
        return $this->hasMany(Exam::class);
    }

    public function blocks():HasMany{
        return $this->hasMany( Block::class,"exam_type_id");
    }
}
