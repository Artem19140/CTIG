<?php

use App\Enums\EmployeeRole;
use App\Http\Controllers\Web\Exam\ExamCheckingController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Exam\ExamDocumentController;
use App\Http\Controllers\Web\Exam\ExamEnrollmentController;
use App\Http\Controllers\Web\Exam\ExamMonitoringController;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamType;
use App\Support\AppMiddleware;

Route::apiResource('exams', ExamController::class)->where(['exam' => '[0-9]+']);

Route::prefix('exams')->group(function(){

    Route::get('available', [ExamEnrollmentController::class, "available"])
        ->can('create', Enrollment::class);

    Route::get('schedule', [ExamController::class, 'schedule'])
        ->name('exams.schedule')
        ->can('viewAny', Exam::class);

    Route::get('create/modal-data', [ExamController::class,'createModalData'])
        ->can('create', Exam::class);   

    Route::get('types', function(){
        return ExamType::select(['id', 'name'])->get();
    });

    Route::middleware(AppMiddleware::EMPLOYEE_HAS_ANY_ROLE. ':'  . EmployeeRole::implode(EmployeeRole::Examiner))
    ->group(function (){
        Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exams.monitoring');
        Route::get('checking', [ExamCheckingController::class, "index"]);
    });

    Route::middleware('can:examiner,exam')
    ->group(function (){
        Route::get('{exam}/documents/protocol', [ExamDocumentController::class, "protocol"])
            ->name('exam.documents.protocol');
        Route::get('{exam}/documents/protocol/available', [ExamDocumentController::class, "protocolAvailable"]);

        Route::get('{exam}/documents/results', [ExamDocumentController::class, 'results'])
            ->name('exam.documents.results');
        Route::get('{exam}/documents/results/available', [ExamDocumentController::class, 'resultsAvailable']);

        Route::get('{exam}/checking', [ExamCheckingController::class, "show"]);

        Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
        Route::put('{exam}/monitoring/protocol-comments', [ExamMonitoringController::class, 'protocolComment']);

        Route::get('{exam}/documents/codes', [ExamDocumentController::class, "codes"])
            ->name('exam.documents.codes');

        Route::get('{exam}/documents/codes/available', [ExamDocumentController::class, "codesAvailable"])
            ->name('exam.documents.codes.available');
    });
        

    Route::get('{exam}/documents/list', [ExamDocumentController::class, 'list'])->name('exam.documents.list')
        ->can('list','exam');

    Route::get('{exam}/documents/list/available', [ExamDocumentController::class, 'listAvailable'])
        ->can('list','exam');
});