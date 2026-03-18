<?php

namespace App\Actions\Counter;

use App\Enums\CounterKey;
use App\Models\Counter;
use Carbon\Carbon;

class GetRegNumberAction{
    public function __construct(
        protected Counter $counter
    ){}
    public function execute(){
        $regNumber = Counter::where('key', CounterKey::RegNumKey)->first();
        $regNumber->lockForUpdate();
        if($regNumber->updated_at->year !== Carbon::now()->year){
            $regNumber->value = Carbon::now()->format('y')."0000";
        }
        $regNumber->value += 1;
        $regNumber->save();
        return $regNumber->value;
    }
}