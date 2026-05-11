<?php

namespace App\Http\Controllers\Web\ActivityLog;

use App\Http\Resources\ActivityLog\ActivityLogResource;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController
{
    public function index(Request $request){
        // $request->validate([
        //     'dateFrom' => ['required', 'date'],
        //     'dateTo'=> ['required', 'date']
        // ]);

        // $dateFrom = Carbon::parse($request->input('dateFrom'))->startOfDay() ?? Carbon::now()->startOfDay();
        // $dateTo = Carbon::parse($request->input('dateTo'))->endOfDay(); whereBetween('created_at', $range)->
        // $range = [$dateFrom, $dateTo];
        $activityLogs = ActivityLog::with(['actor', 'center'])
            ->latest()
            ->simplePaginate();
        return Inertia::render('SuperAdmin/Logs', [
            'activityLogs' => ActivityLogResource::collection($activityLogs)
        ]);
        return ActivityLogResource::collection($activityLogs);
    }
}
