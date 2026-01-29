<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskVariant extends Model
{
    /** @use HasFactory<\Database\Factories\TaskVariantFactory> */
    use HasFactory;

    public function answers() : HasMany{
        return $this->hasMany(Answer::class, 'task_variant_id');
    }

    public function creator(): BelongsTo{
        return $this->belongsTo( User::class, "creator_id");
    }
}
