<?php

namespace App\Domain\Counter;

use App\Domain\Center\CenterContext;
use App\Models\Exam;
use Carbon\Carbon;

class GetSessionNumberQuery{
    public function execute(Carbon $beginTime):int{
        $sessionNumber = Exam::query()
            ->forCenter(app(CenterContext::class)->id())
            ->whereBetween('begin_time', [
                $beginTime->copy()->startOfYear(),
                $beginTime->copy()->subDay()->endOfDay()
            ])
            ->notCancelled()
            ->hasAttempts()
            ->get()
            ->groupBy(function($exam) {
                return $exam->begin_time->copy()->toDateString();
            })
            ->count();
        return max(1, $sessionNumber);
    }
}