<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subblock extends Model
{
    /** @use HasFactory<\Database\Factories\SubblockFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'exam_block_id',
        'creator_id',
        'is_active',
        'min_mark',
        'order'
    ];

    public function tasks():HasMany{
        return $this->hasMany(Task::class, 'subblock_id');    
    }

    public function creator(): BelongsTo{
        return $this->belongsTo( User::class, "creator_id");
    }

    public function block(): BelongsTo  {
        return $this->belongsTo( ExamBlock::class, "exam_block_id");
    }
}
