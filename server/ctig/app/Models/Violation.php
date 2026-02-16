<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    /** @use HasFactory<\Database\Factories\ViolationFactory> */
    use HasFactory;
    protected $fillable = [
        'exam_attempt_id',
        'type'
    ];
}
