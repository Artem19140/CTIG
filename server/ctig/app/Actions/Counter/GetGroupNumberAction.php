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
        $groupNumber = Counter::where('key', CounterKey::GroupKey)->first();
        $groupNumber->lockForUpdate();
        if($groupNumber->updated_at->day !== Carbon::now()->day){
            $groupNumber->value = 0;
        }
        $groupNumber->value += 1;
        $groupNumber->save();
        return $groupNumber->value;
    }
}