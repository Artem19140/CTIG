<?php

use App\Http\Controllers\Web\Attempt\AttemptAnswerController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use App\Support\AppMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([
    AppMiddleware::REQUEST_TIME_MEASURE, 
    AppMiddleware::LOG_CONTEXT,
    'auth:foreignNationals'
])
->group(function (){
    Route::prefix('attempts')
    ->group(function(){
        Route::put('{attempt}/finish', [AttemptController::class, 'finish'])
            ->name('attempts.finish')
            ->can('attempt-access', 'attempt');
            
        Route::get('{attempt}/preparing', [AttemptController::class, 'preparing'])
            ->name('attempts.preparing')
            ->can('attempt-access', 'attempt');

        Route::get('{attempt}', [AttemptController::class, 'show'])
            ->name('attempts.show')
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}', [AttemptController::class, 'start'])
            ->name('attempts.start')
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}/answers/{attemptAnswer}', [AttemptAnswerController::class, 'update'])
            ->name('attempts.answers.update')
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}/answers/{attemptAnswer}/audio', [AttemptAnswerController::class, 'audioPlayed'])
            ->name('attempts.answers.update.audio')
            ->can('attempt-access', 'attempt');
    });
    
});