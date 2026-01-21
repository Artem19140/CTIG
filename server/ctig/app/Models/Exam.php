<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'exam_date',
        'is_conducted',
        'exam_type_id',
        'creator_id',
        'tester_id',
        'session_number'
    ];
    public function examType(): BelongsTo{
        return $this->belongsTo(ExamType::class);
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function testers():BelongsToMany{
        return $this->belongsToMany(User::class, 'tester_exam','exam_id', 'tester_id');
    }

    public function migrants():BelongsToMany{
        return $this->belongsToMany(Migrant::class);
    }
}
