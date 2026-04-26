<?php

namespace App\Models;

use App\Enums\AttemptStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Attempt extends Model
{
    /** @use HasFactory<\Database\Factories\AttemptFactory> */
    use HasFactory;
    public const int MIN_TIME_FROM_START_TO_FINISH_MINUTES = 10;
    protected $fillable = [
        'foreign_national_id',
        'exam_id',
        'finished_at',
        'expired_at',
        'status',
        'is_passed',
        'total_mark',
        'started_at',
        'ban_reason',
        'ban_by_id',
        'center_id',
        'solved',
        'enrollment_id',
        'banned_at',
        'checked_at',
        'last_activity_at',
        'speaking_finished_at'
    ];
    protected $casts = [
        'status' => AttemptStatus::class,
        'expired_at' => 'datetime',
        'finished_at' => 'datetime',
        'started_at' => 'datetime',
        'is_passed' => 'boolean',
        'banned_at' => 'datetime',
        'checked_at'  => 'datetime',
        'last_activity_at' => 'datetime',
        'speaking_finished_at' => 'datetime',
    ];

    public function isExpired(): bool{
        return $this->expired_at->gte(Carbon::now($this->time_zone));
    }
    
    public function finish(): void{
        $this->status = AttemptStatus::Finished;
        $this->finished_at = Carbon::now($this->time_zone);
    }
    public function isStarted(): bool{
        return $this->started_at !== null;
    }

    public function isChecked(): bool{
        return $this->checked_at !== null;
    }

    public function isBanned(): bool{
        return $this->banned_at !== null;
    }

    public function isFinished(): bool{
        return $this->finished_at !== null;
    }

    public function foreignNational(): BelongsTo{
        return $this->belongsTo(ForeignNational::class, 'foreign_national_id');
    }

    public function exam(): BelongsTo{
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function answers():HasMany{
        return $this->hasMany(AttemptAnswer::class, 'attempt_id');
    }

    public function violations(): HasMany{
        return $this->hasMany(Violation::class, 'attempt_id');
    }

    public function center(): BelongsTo{
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function enrollment(): BelongsTo{
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    protected function timeZone(): Attribute
    {
        return Attribute::get(function () {
            return $this->center->time_zone;
        });
    }

    public function canBeAutomaticallyFinalized():bool{
        return $this->exam->type->need_human_check;
    }

    public function taskVariants():BelongsToMany{
        return $this->belongsToMany(TaskVariant::class, 'attempt_answers');
    }

    public function hasUncheckedAnswers():bool{
        return $this->answers()->notChecked()->exists();
    }

    public function scopeWhereCreatedAtMore(Builder $query, Carbon $date){
        return $query->where('created_at', '>', $date);
    }

    public function scopeWhereCreatedAtLess(Builder $query, Carbon $date){
        return $query->where('created_at', '<', $date);
    }

    public function status(){
        if($this->isBanned()){
            return AttemptStatus::Banned;
        }
        if($this->isFinished()){
            return AttemptStatus::Finished;
        }
        if($this->isStarted()){
            return AttemptStatus::Active;
        }
        return AttemptStatus::Pending;
    }

}
