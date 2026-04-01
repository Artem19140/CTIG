<?php

namespace App\Models;

use App\Enums\CounterKey;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'key',
        'value',
        'organization_id'
    ];

    protected $casts = [
        'key' => CounterKey::class
    ];

    public function get(CounterKey $key){
       return static::where('key', $key)->value('value') ?? null;
    }

    public function set(CounterKey $key, int $value){
        
    }

    public function increase(CounterKey $key, int $step = 1){

    }
}
