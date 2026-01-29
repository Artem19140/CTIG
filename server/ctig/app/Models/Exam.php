<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\ExamStatus;

class Exam extends Model
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'begin_time',
        'is_finished',
        'exam_type_id',
        'creator_id',
        'session_number', //При переносе менять! Или как блин!? После проведения сессию только установить!
        'capacity',
        'status',
        'comment',
        'group',
        'end_time',
        'exam_address_id',
        'exam_date'
    ];

    protected $casts = [
        'status' => ExamStatus::class
    ];

    public function examType(): BelongsTo{
        return $this->belongsTo(ExamType::class);
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function testers():BelongsToMany{
        return $this->belongsToMany(User::class, 'exam_tester','exam_id', 'tester_id');
    }

    public function students():BelongsToMany{
        return $this->belongsToMany(Student::class);
    }

    public function attemps(): HasMany{
        return $this->hasMany(ExamAttempt::class, 'exam_id');
    }

    public function documents(): MorphMany{
        return $this->morphMany(Document::class, 'documentable');
    }

    public function codes(): HasMany{
        return $this->hasMany(ExamCode::class, 'exam_id');
    }

    public function address(): BelongsTo{
        return $this->belongsTo(ExamAddress::class, 'exam_address_id');
    }

    function isCodesGeterated(){
        return $this->is_codes_generate;
    }
}
