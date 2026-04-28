<?php

namespace App\Models\Scopes;

trait BelongsToCenter
{
    protected static function bootBelongsToCenter(){
        static::addGlobalScope(new CenterScope);
    }
}
