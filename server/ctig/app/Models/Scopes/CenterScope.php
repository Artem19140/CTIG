<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CenterScope implements Scope
{
    public function apply(Builder $builder, Model $model): void{
        $builder->where('center_id', request()->user()->center_id);
    }
}
