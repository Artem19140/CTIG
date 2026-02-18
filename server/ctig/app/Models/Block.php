<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{

    protected $fillable = [
        'name',
        'min_mark',
        'exam_type_id',
        'is_active',
        'order'
    ];

    protected $casts= [
        'is_active' => 'boolean'
    ];

    public function examType(): BelongsTo{
        return $this->belongsTo( ExamType::class, "exam_type_id");
    }

    public function subblocks():HasMany{
        return $this->hasMany( Subblock::class,"block_id");
    }
}
