<?php

namespace App\Http\Controllers\Web\Statistics;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminStatisticsController
{
    public function index(
        Request $request
    ){
        $request->validate([
            'dateFrom' => ['required', 'date'],
            'dateTo'=> ['required', 'date']
        ]);

        $dateFrom = Carbon::parse($request->input('dateFrom'))->startOfDay();
        $dateTo = Carbon::parse($request->input('dateTo'))->endOfDay();
        $range = [$dateFrom, $dateTo];
        return response()->json([
            'examsCount' => Exam::whereBetween('created_at', $range)->count(),
            'enrollmentsCount' => Enrollment::whereBetween('created_at', $range)->count(),
            'foreignNationalsCount' => ForeignNational::whereBetween('created_at', $range)->count(),
            'attemptsCount' => Attempt::whereBetween('created_at', $range)->count(),
            'pendingAttempts' => Attempt::whereBetween('created_at', $range)->where('status', AttemptStatus::Pending)->count()
        ]);
    }
}
