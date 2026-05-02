<?php

use App\Domain\Attempt\Action\CloseAbandonedAttemptsAction;
use App\Domain\Exam\Action\ClearExpiredExamCodesAction;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(ClearExpiredExamCodesAction::class)->execute();
})->monthly();

Schedule::call(function () {
    app(CloseAbandonedAttemptsAction::class)->execute();
})->everyFiveMinutes();