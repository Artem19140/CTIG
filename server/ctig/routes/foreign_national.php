<?php

use App\Http\Controllers\Web\Attempt\AttemptAnswerController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:foreignNationals')->group(function (){
    Route::prefix('attempts')->group(function(){
        Route::put('{attempt}/finish', [AttemptController::class, 'finish'])
            ->can('attempt-access', 'attempt');
            
        Route::get('{attempt}/before', [AttemptController::class, 'before'])->name('attempts.before')
            ->can('attempt-access', 'attempt');

        Route::get('{attempt}', [AttemptController::class, 'show'])->name('attempts') 
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}', [AttemptController::class, 'start'])
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}/answers/{attemptAnswer}', [AttemptAnswerController::class, 'update'])
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}/answers/{attemptAnswer}/audio', [AttemptAnswerController::class, 'audioPlayed'])
            ->can('attempt-access', 'attempt');
    });
    
});