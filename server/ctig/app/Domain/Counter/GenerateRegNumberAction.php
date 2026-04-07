<?php

namespace App\Domain\Counter;

use App\Enums\CounterKey;
use App\Models\Counter;
use Carbon\Carbon;

class GenerateRegNumberAction{
    public function __construct(
        protected Counter $counter
    ){}
    public function execute(){
        $regNumber = Counter::where('key', CounterKey::RegNumKey)->lockForUpdate()->first();
        if($regNumber->updated_at->year !== Carbon::now()->year){
            $regNumber->value = Carbon::now()->format('y')."0000";
            $regNumber->updated_at = Carbon::now();
        }
        $regNumber->value += 1;
        $regNumber->save();
        return $regNumber->value;
    }
}