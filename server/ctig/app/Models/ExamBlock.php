<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamBlock extends Model
{
    /** @use HasFactory<\Database\Factories\ExamBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'min_mark',
        'exam_type_id',
        'creator_id',
        'is_active',
        'order'
    ];

    protected $casts= [
        'is_active' => 'boolean'
    ];

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function examType(): BelongsTo{
        return $this->belongsTo( ExamType::class, "exam_type_id");
    }

    public function subblocks():HasMany{
        return $this->hasMany( Subblock::class,"exam_block_id");
    }
}
