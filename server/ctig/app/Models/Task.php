<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TaskType;

class Task extends Model
{
    protected $fillable = [
        'type',
        'subblock_id',
        'fipi_guid',
        'order'
    ];

    protected $casts = [
        'type' => TaskType::class
    ];

    public function variants() : HasMany{
        return $this->hasMany(TaskVariant::class, 'task_id');
    }

    public function subblock(): BelongsTo{
        return $this->belongsTo( Subblock::class, "subblock_id");
    }
}
