<?php

namespace App\Domain\Counter;

use App\Domain\Center\CenterContext;
use App\Enums\CounterKey;
use App\Exceptions\Couner\CounterNotFoundException;
use App\Models\Counter;
use Carbon\Carbon;

class GenerateGroupNumberAction{

    public function execute():int{
        $groupNumber = Counter::where('key', CounterKey::Group)
            ->forCenter(app(CenterContext::class)->id())
            ->lockForUpdate()
            ->first();
        if(!$groupNumber){
            throw new CounterNotFoundException(CounterKey::Group);
        }
        if($this->isNewDay($groupNumber)){
            $groupNumber->value = 0;
            $groupNumber->updated_at = Carbon::now();
        }
        $groupNumber->value += 1;
        $groupNumber->save();
        return $groupNumber->value;
    }
    
    protected function isNewDay(Counter $groupNumber):bool{
        return $groupNumber->updated_at->toDateString() !== Carbon::now()->toDateString();
    }
}