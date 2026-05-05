<?php

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Attempt\AttemptAnswerController;
use App\Http\Controllers\Web\Attempt\AttemptCheckingController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use App\Http\Controllers\Web\Attempt\AttemptSpeakingController;
use App\Http\Controllers\Web\Attempt\AttemptViolationController;

Route::prefix('attempts')->middleware(['user.has.any.role:' . UserRoles::Examiner->value])
    ->group(function(){
        Route::put('{attempt}/ban', [AttemptController::class, 'ban'])->name('attempts.ban');

        Route::get('{attempt}/checking/tasks', [AttemptCheckingController::class, 'show'])->name('attempts.checking.tasks');
        Route::post('{attempt}/checking/finish', [AttemptCheckingController::class, 'finish'])->name('attempts.checking.finish');

        Route::get('{attempt}/speaking', [AttemptSpeakingController::class, 'show'])->name('attempts.speaking');
        Route::post('{attempt}/speaking/finish', [AttemptSpeakingController::class, 'finish'])->name('attempts.speaking.finish');
        Route::post('{attempt}/speaking/start', [AttemptSpeakingController::class, 'start'])->name('attempts.speaking.start');

        Route::get('{attempt}/violations', [AttemptViolationController::class, 'index'])->name('attempts.violations.index');
        Route::post('{attempt}/violations', [AttemptViolationController::class, 'store'])->name('attempts.violations.store');
        Route::delete('{attempt}/violations/{violation}', [AttemptViolationController::class, 'destroy'])->name('attempts.violations.destroy');
        Route::patch('{attempt}/violations/{violation}', [AttemptViolationController::class, 'update'])->name('attempts.violations.update');

        
});
Route::put('answers/{attemptAnswer}/rate', [AttemptAnswerController::class, 'rate']);
