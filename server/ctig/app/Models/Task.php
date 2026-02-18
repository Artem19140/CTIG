<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TaskTypeEnum;

class Task extends Model
{
    protected $fillable = [
        'type',
        'subblock_id',
        'order'
    ];

    protected $casts = [
        'type' => TaskTypeEnum::class
    ];

    public function variants() : HasMany{
        return $this->hasMany(TaskVariant::class, 'task_id');
    }

    public function subblock(): BelongsTo{
        return $this->belongsTo( Subblock::class, "subblock_id");
    }
}
