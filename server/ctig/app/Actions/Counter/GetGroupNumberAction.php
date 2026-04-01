<?php

namespace App\Actions\Counter;

use App\Enums\CounterKey;
use App\Models\Counter;
use Carbon\Carbon;

class GetGroupNumberAction{
    public function __construct(
        protected Counter $counter
    ){}
    public function execute(){
        $groupNumber = Counter::where('key', CounterKey::GroupKey)->lockForUpdate()->first();
        if($groupNumber->updated_at->toDateString() !== Carbon::now()->toDateString()){
            $groupNumber->value = 0;
            $groupNumber->updated_at = Carbon::now();
        }
        $groupNumber->value += 1;
        $groupNumber->save();
        return $groupNumber->value;
    }
}