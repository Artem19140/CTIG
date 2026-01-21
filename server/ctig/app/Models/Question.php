<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'contain',
        'creator_id',
        'test_id',
        'exam_block_id'
    ];

    public function answers() : HasMany{
        return $this->hasMany(Answer::class);
    }

    public function creator(): BelongsTo{
        return $this->belongsTo( User::class, "creator_id");
    }

    public function examBlockBelong(): BelongsTo{
        return $this->belongsTo( ExamBlock::class, "exam_block_id");
    }
}
