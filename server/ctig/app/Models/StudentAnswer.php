<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'exam_id',
        'exam_block_id',
        'task_id',
        'is_right',
        'student_answer'
    ];
}
