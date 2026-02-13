<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\TaskType;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'creator_id',
        'type',
        'exam_block_id',
        'fipi_guid'
    ];

    protected $casts = [
        'type' => TaskType::class
    ];

    public function variants() : HasMany{
        return $this->hasMany(TaskVariant::class, 'task_id');
    }

    public function creator(): BelongsTo{
        return $this->belongsTo( User::class, "creator_id");
    }

    public function examBlockBelong(): BelongsTo{
        return $this->belongsTo( ExamBlock::class, "exam_block_id");
    }
}
