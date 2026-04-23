<?php

namespace App\Domain\ForeignNational\Action;

use App\Models\ForeignNational;
use Carbon\Carbon;

class ClearForeignNationalExpiredTTLActon{
    public function execute(){
        ForeignNational::where('created_at', '<', Carbon::now()->subYears(ForeignNational::STORAGE_TTL))
            ->delete();
    }
}