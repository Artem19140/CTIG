<?php

namespace App\Domain\Counter;

use App\Domain\Center\CenterContext;
use App\Enums\CounterKey;
use App\Exceptions\Couner\CounterNotFoundException;
use App\Models\Counter;
use Carbon\Carbon;

class GenerateRegNumberAction{
    public function execute():int{
        $regNumber = Counter::query()
            ->forCenter(app(CenterContext::class)->id())
            ->where('key', CounterKey::RegNum)->lockForUpdate()->first();
        if(!$regNumber){
            throw new CounterNotFoundException(CounterKey::RegNum);
        }
        if($this->isNewYear($regNumber)){
            $regNumber->value = \intval(Carbon::now()->format('y')."0000");
            $regNumber->updated_at = Carbon::now();
        }
        
        $regNumber->value += 1;
        $regNumber->save();
        return $regNumber->value;
    }
    protected function isNewYear(Counter $regNumber):bool{
        return $regNumber->updated_at->year !== Carbon::now()->year;
    }
}