<?php

use App\Http\Controllers\Web\Attempt\AttemptAnswerController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:foreignNationals')->group(function (){
    Route::prefix('attempts')->group(function(){
        Route::put('{attempt}/finish', [AttemptController::class, 'finish'])
            ->can('attempt-access', 'attempt');
            
        Route::get('{attempt}/preparing', [AttemptController::class, 'preparing'])->name('attempts.preparing')
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

// $attempt->latest();
// return match($attempt){
//     $attempt->isBanned() => throw new AttemptBannedException,
//     $attempt->isFinished() => throw new AttemptFinishedException,
//     $attempt->isExpired() => throw new AttemptExpiredException,
//     $attempt->isPending()  => route('attempts.pending', ['attempt' => $attempt]),
//     default => route('attempts.show' => , ['attempt' => $attempt])
// }