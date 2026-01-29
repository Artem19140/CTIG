<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamBlock extends Model
{
    /** @use HasFactory<\Database\Factories\ExamBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'min_mark',
        'exam_type_id',
        'creator_id',
        'is_actual'
    ];

    public function tasks():HasMany{
        return $this->hasMany(Task::class, 'exam_block_id');    
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function examType(): BelongsTo{
        return $this->belongsTo( ExamType::class, "exam_type_id");
    }
}
