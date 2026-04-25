<?php

namespace App\Http\Controllers\Web\Statistics;

use App\Enums\AttemptStatus;
use App\Http\Requests\Statistics\StatisticsRequest;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatisticsController
{
    public function index(StatisticsRequest $request){
        $from = Carbon::parse($request->validated('dateFrom'))->startOfDay();
        $to = Carbon::parse($request->validated('dateTo'))->endOfDay();
        $examsCount = Exam::whereBeginTimeMore($from)
            ->whereBeginTimeLess($to)
            ->notCancelled()
            ->count();
        $attemptsTakersCount = ForeignNational::whereHas('attempts', function(Builder $query)use($from, $to){
            $query->whereBetween('started_at',[
                $from,
                $to
            ]);
        })->count();

        $attemptsQuery = Attempt::whereCreatedAtMore($from)
            ->whereCreatedAtLess($to);
            
        $attemptsCount = (clone $attemptsQuery)->count();


        $successfulAttemptsCount = (clone $attemptsQuery)
                            ->where('is_passed', true)
                            ->count();

        $failedAttemptsCount = (clone $attemptsQuery)
                            ->where('is_passed', false)
                            ->where('status', '<>', AttemptStatus::Banned)
                            ->count();

        $bannedAttemptsCount = (clone $attemptsQuery)
            ->where('status', AttemptStatus::Banned)
            ->count();
        return response()->json([
            'examsCount' => $examsCount,
            'attemptsCount' => $attemptsCount,
            'attemptsTakersCount' => $attemptsTakersCount,
            'failedAttemptsCount' => $failedAttemptsCount,
            'successfulAttemptsCount' => $successfulAttemptsCount,
            'bannedAttemptsCount' => $bannedAttemptsCount
        ]);
    }
}
