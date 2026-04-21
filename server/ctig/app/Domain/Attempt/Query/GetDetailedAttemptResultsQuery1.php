<?php 

namespace App\Domain\Attempt\Query;

use App\Models\Attempt;

class GetDetailedAttemptResultsQuery1{
    public static function execute(Attempt $attempt){
        $attempt->loadMissing('answers:id,mark');
        $attempt->answers->groupBy();
    }
}