<?php

use App\Http\Controllers\Web\Attempt\AttemptAnswerController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use Illuminate\Support\Facades\Route;


Route::prefix('attempts')
->can('attempt-access', 'attempt')
->group(function(){
    Route::put('{attempt}/finish', [AttemptController::class, 'finish'])
        ->name('attempts.finish');
        
    Route::get('{attempt}', [AttemptController::class, 'show'])
        ->name('attempts.show');

    Route::put('{attempt}', [AttemptController::class, 'start'])
        ->name('attempts.start');

    Route::put('{attempt}/answers/{attemptAnswer}', [AttemptAnswerController::class, 'update'])
        ->name('attempts.answers.update');

    Route::put('{attempt}/answers/{attemptAnswer}/audio', [AttemptAnswerController::class, 'audioPlayed'])
        ->name('attempts.answers.update.audio');
        
});

