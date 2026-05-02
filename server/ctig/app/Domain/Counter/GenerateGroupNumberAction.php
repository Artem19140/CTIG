<?php

namespace App\Domain\Counter;

use App\Enums\CounterKey;
use App\Models\Counter;
use Carbon\Carbon;

class GenerateGroupNumberAction{
    public function __construct(
        protected Counter $counter
    ){}
    public function execute(){
        $groupNumber = Counter::where('key', CounterKey::GroupKey)->lockForUpdate()->first();
        if($this->isNewDay($groupNumber)){
            $groupNumber->value = 0;
            $groupNumber->updated_at = Carbon::now();
        }
        $groupNumber->value += 1;
        $groupNumber->save();
        return $groupNumber->value;
    }
    
    protected function isNewDay(Counter $groupNumber){
        return $groupNumber->updated_at->toDateString() !== Carbon::now()->toDateString();
    }
}