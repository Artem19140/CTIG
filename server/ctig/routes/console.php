<?php

use App\Domain\Attempt\Action\CloseAbandonedAttemptsAction;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    DB::table('enrollments')
        ->where('exam_code_expired_at', '<', Carbon::now())
        ->whereNotNull('exam_code')
        ->update(['exam_code' => null]);
})->monthly();

Schedule::call(function () {
    app(CloseAbandonedAttemptsAction::class)->execute();
})->everyFiveMinutes();