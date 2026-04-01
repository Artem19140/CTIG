<?php

namespace App\Actions\Counter;

use App\Models\Exam;
use Carbon\Carbon;
class GetSessionNumberAction{
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
        return $sessionNumber;
    }
}