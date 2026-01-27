<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'hash_name',
        'creator_id',
        'documentable_id',
        'documentable_type'
    ];
    public function documentable(): MorphTo{
        return $this->morphTo();
    }

    public function creator():BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }
}
