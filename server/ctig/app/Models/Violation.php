<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Violation extends Model
{
    /** @use HasFactory<\Database\Factories\ViolationFactory> */
    use HasFactory;
    protected $fillable = [
        'attempt_id',
        'exam_id',
        'comment'
    ];

    public function attempt():BelongsTo{
        return $this->belongsTo(Attempt::class, 'attempt_id');
    }
}
