<?php

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Exam\ExamCheckingController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Exam\ExamDocumentController;
use App\Http\Controllers\Web\Exam\ExamEnrollmentController;
use App\Http\Controllers\Web\Exam\ExamMonitoringController;
use App\Models\ExamType;
use App\Support\AppMiddleware;

Route::apiResource('exams', ExamController::class)->where(['exam' => '[0-9]+'])
    ->middlewareFor(['destroy', 'store', 'update'], [AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(UserRoles::Scheduler)])
    ->middlewareFor(
        ['show', 'index'], 
        [AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(
            UserRoles::Operator, 
            UserRoles::Director, 
            UserRoles::Examiner, 
            UserRoles::Scheduler
        )
    ]);


Route::prefix('exams')->group(function(){

    Route::get('available', [ExamEnrollmentController::class, "available"])
        ->middleware(AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(UserRoles::Operator));

    Route::get('schedule', [ExamController::class, 'schedule'])->name('exams.schedule')
        ->middleware([AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(
            UserRoles::Operator,
            UserRoles::Director,
            UserRoles::Scheduler
        )]);

    Route::middleware(AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(UserRoles::Scheduler))
    ->group(function (){

        Route::get('create/modal-data', [ExamController::class,'createModalData']);
       
        Route::get('types', function(){
            return ExamType::select(['id', 'name'])->get();
        });
        
    });

    Route::middleware(AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(UserRoles::Examiner))
    ->group(function (){
        Route::get('checking', [ExamCheckingController::class, "index"]);
        Route::get('{exam}/checking', [ExamCheckingController::class, "show"]);

        Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exams.monitoring');
        Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
        Route::put('{exam}/monitoring/protocol-comments', [ExamMonitoringController::class, 'updateProtocolComment']);

        Route::get('{exam}/documents/codes', [ExamDocumentController::class, "codes"])->name('exam.documents.codes');
        Route::get('{exam}/documents/codes/available', [ExamDocumentController::class, "codesAvailable"])->name('exam.documents.codes.available');
    });
    
    
    Route::middleware(AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(UserRoles::Examiner, UserRoles::Director))
    ->group(function (){
        Route::get('{exam}/documents/protocol', [ExamDocumentController::class, "protocol"])->name('exam.documents.protocol');
        Route::get('{exam}/documents/protocol/available', [ExamDocumentController::class, "protocolAvailable"]);

        Route::get('{exam}/documents/results', [ExamDocumentController::class, 'results'])->name('exam.documents.results');
        Route::get('{exam}/documents/results/available', [ExamDocumentController::class, 'resultsAvailable']);

        
    });

    Route::get('{exam}/documents/list', [ExamDocumentController::class, 'list'])->name('exam.documents.list')
    ->middleware([AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(
            UserRoles::Operator, 
            UserRoles::Director, 
            UserRoles::Examiner
        )]);

    Route::get('{exam}/documents/list/available', [ExamDocumentController::class, 'listAvailable'])
    ->middleware([AppMiddleware::USER_HAS_ANY_ROLE. ':'  . UserRoles::implode(
            UserRoles::Operator, 
            UserRoles::Director, 
            UserRoles::Examiner
        )]);
});