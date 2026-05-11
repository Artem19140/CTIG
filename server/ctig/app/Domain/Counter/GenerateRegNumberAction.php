<?php

namespace App\Domain\Counter;

use App\Enums\CounterKey;
use App\Models\Counter;
use Carbon\Carbon;

class GenerateRegNumberAction{
    public function execute(){
        $regNumber = Counter::where('key', CounterKey::RegNum)->lockForUpdate()->first();
        if($this->isNewYear($regNumber)){
            $regNumber->value = \intval(Carbon::now()->format('y')."0000");
            $regNumber->updated_at = Carbon::now();
        }
        $regNumber->value += 1;
        $regNumber->save();
        return $regNumber->value;
    }
    protected function isNewYear(Counter $regNumber){
        return $regNumber->updated_at->year !== Carbon::now()->year;
    }
}