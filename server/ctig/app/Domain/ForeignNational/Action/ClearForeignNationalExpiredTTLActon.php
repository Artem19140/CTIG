<?php

namespace App\Domain\ForeignNational\Action;

use App\Models\ForeignNational;
use Carbon\Carbon;

class ClearForeignNationalExpiredTTLActon{
    public function execute(){
        ForeignNational::where('storage_expired_at', '<', Carbon::now())
            ->delete();
    }
}