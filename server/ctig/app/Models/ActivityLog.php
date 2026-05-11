<?php

namespace App\Models;

use App\Enums\Event;
use App\Enums\Resource;
use App\Support\TimePresenter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_table';
    protected $fillable = [
        'resource',
        'evente',
        'center_id',
        'context',
        'actor_id',
        'meta',
        'actor_type',
        'event'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'context' => 'array',
        'meta' => 'array',
        'event' => Event::class,
        'resource' => Resource::class
    ];

    public function actor():BelongsTo{
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function center():BelongsTo{
        return $this->belongsTo(Center::class, 'center_id');
    }

    protected function createdAtLocal(): Attribute{
        return Attribute::get(function () {
            return TimePresenter::forCenter($this->created_at, $this->center);
        });
    }
}
