<?php

namespace App\Domain\Counter;

use App\Models\Exam;
use Carbon\Carbon;
class GetSessionNumberQuery{
    public function execute(Carbon $beginTime):int{
        
        $sessionNumber = Exam::whereBetween('begin_time', [
                                $beginTime->copy()->startOfYear(),
                                $beginTime->copy()->subDay()->endOfDay()
                            ])
                            ->where('is_cancelled', false)
                            ->get()
                            ->groupBy(function($exam) {
                                return $exam->begin_time->copy()->toDateString();
                            })
                            ->count();
        if($sessionNumber === 0){
            $sessionNumber += 1;
        }
        return $sessionNumber;
    }
}