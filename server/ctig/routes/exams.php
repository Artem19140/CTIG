<?php

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Exam\ExamCheckingController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Exam\ExamDocumentController;
use App\Http\Controllers\Web\Exam\ExamEnrollmentController;
use App\Http\Controllers\Web\Exam\ExamMonitoringController;
use App\Models\ExamType;

Route::apiResource('exams', ExamController::class)->where(['exam' => '[0-9]+'])
    ->only(['show', 'index'])
    ->middleware(['user.has.any.role:' . UserRoles::implode([UserRoles::Operator, UserRoles::Director, UserRoles::Examiner, UserRoles::Scheduler])]);

Route::apiResource('exams', ExamController::class)->where(['exam' => '[0-9]+'])
    ->except(['show', 'index'])
    ->middleware(['user.has.any.role:' . UserRoles::implode([UserRoles::Scheduler])]);

Route::prefix('exams')->group(function(){
    Route::get('available', [ExamEnrollmentController::class, "available"]);

    Route::get('checking', [ExamCheckingController::class, "index"]);
    Route::get('{exam}/checking', [ExamCheckingController::class, "show"]);

    Route::get('types', function(){
        return ExamType::select(['id', 'name'])->get();
    });

    Route::get('create/modal-data', [ExamController::class,'createModalData']);
    Route::get('schedule', [ExamController::class, 'schedule'])->name('exams.schedule');

    Route::middleware(['user.has.any.role:' . UserRoles::Examiner->value])->group(function (){
        Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exam.monitoring');
        Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
        Route::put('{exam}/monitoring/protocol-comments', [ExamMonitoringController::class, 'updateProtocolComment']);
    });
    
    Route::prefix('{exam}/documents')->group(function(){
        Route::middleware(['user.has.any.role:' . UserRoles::implode([UserRoles::Examiner, UserRoles::Director])])->group(function (){
            Route::get('codes', [ExamDocumentController::class, "codes"])->name('exam.documents.codes');
            Route::get('codes/available', [ExamDocumentController::class, "codesAvailable"])->name('exam.documents.codes.available');

            Route::get('protocol', [ExamDocumentController::class, "protocol"])->name('exam.documents.protocol');
            Route::get('protocol/available', [ExamDocumentController::class, "protocolAvailable"]);

            Route::get('results', [ExamDocumentController::class, 'results'])->name('exam.documents.results');
            Route::get('results/available', [ExamDocumentController::class, 'resultsAvailable']);
    });
        Route::get('list', [ExamDocumentController::class, 'list'])->name('exam.documents.list')->middleware(['user.has.any.role:' . UserRoles::Operator->value]);
        Route::get('list/available', [ExamDocumentController::class, 'listAvailable'])->middleware(['user.has.any.role:' . UserRoles::Operator->value]);
    });
});