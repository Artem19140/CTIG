<?php

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Address\AddressController;
use App\Http\Controllers\Web\Attempt\AttemptCheckingController;
use App\Http\Controllers\Web\Attempt\AttemptSpeakingController;
use App\Http\Controllers\Web\Attempt\AttemptViolationController;
use App\Http\Controllers\Web\Enrollment\EnrollmentDocumentController;
use App\Http\Controllers\Web\Exam\ExamCheckingController;
use App\Http\Controllers\Web\Exam\ExamEnrollmentController;
use App\Http\Controllers\Web\File\FileController;
use App\Http\Controllers\Web\ForeignNational\ForeignNationalExportController;
use App\Http\Controllers\Web\Statistics\StatisticsController;
use App\Http\Controllers\Web\User\UserController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Exam\ExamDocumentController;
use App\Http\Controllers\Web\Enrollment\EnrollmentController;
use App\Http\Controllers\Web\Exam\ExamMonitoringController;
use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\Center\CenterController;
use App\Http\Controllers\Web\Report\ReportController;
use App\Http\Controllers\Web\ForeignNational\ForeignNationalController;
use App\Http\Controllers\Web\Attempt\AttemptAnswerController;
use App\Models\ExamType;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'user.active', 'center.active', 'password.change'])->group(function(){
    Route::apiResource('foreign-nationals', ForeignNationalController::class)->middleware(['user.has.role:' . UserRoles::Operator->value]);

    Route::prefix('enrollments')->middleware(['user.has.role:' . UserRoles::Operator->value])->group(function(){
        
        Route::apiResource('', EnrollmentController::class);
        Route::put('{enrollment}/payment', [EnrollmentController::class, 'changePayment'])->name('enrollments.change.payment');
        Route::get('{enrollment}/statements', [EnrollmentDocumentController::class, 'statement'])->name('enrollments.statements');
        
    });

    Route::middleware(['user.has.role:' . UserRoles::Director->value])->group(function() {
        Route::get('foreign-nationals/export', [ForeignNationalExportController::class, 'export'])->name('foreign-nationals.export');
        Route::get('foreign-nationals/export/available', [ForeignNationalExportController::class, 'exportAvailable']);
        Route::get('statistics', [StatisticsController::class, 'index']);
    });
    

    Route::apiResource('exams', ExamController::class)->where(['exam' => '[0-9]+']);
    Route::prefix('exams')->group(function(){
        Route::get('available', [ExamEnrollmentController::class, "available"]);

        Route::get('checking', [ExamCheckingController::class, "index"]);
        Route::get('{exam}/checking', [ExamCheckingController::class, "show"]);

        Route::get('types', function(){
            return ExamType::select(['id', 'name'])->get();
        });

        Route::get('create/modal-data', [ExamController::class,'createModalData']);
        Route::get('schedule', [ExamController::class, 'schedule'])->name('exams.schedule');

        Route::middleware(['user.has.role:' . UserRoles::Examiner->value])->group(function (){
            Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exam.monitoring');
            Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
            Route::put('{exam}/monitoring/protocol-comments', [ExamMonitoringController::class, 'updateProtocolComment']);
        });
        
        Route::prefix('{exam}/documents')->group(function(){
            Route::middleware(['user.has.role:' . UserRoles::Examiner->value . ',' . UserRoles::Director->value])->group(function (){
                Route::get('codes', [ExamDocumentController::class, "codes"])->name('exam.documents.codes');
                Route::get('codes/available', [ExamDocumentController::class, "codesAvailable"])->name('exam.documents.codes.available');
                Route::get('protocol', [ExamDocumentController::class, "protocol"])->name('exam.documents.protocol');
                Route::get('protocol/available', [ExamDocumentController::class, "protocolAvailable"]);
                Route::get('results', [ExamDocumentController::class, 'results'])->name('exam.documents.results');
                Route::get('results/available', [ExamDocumentController::class, 'resultsAvailable']);
        });
            Route::get('list', [ExamDocumentController::class, 'list'])->name('exam.documents.list')->middleware(['user.has.role:' . UserRoles::Operator->value]);
            Route::get('list/available', [ExamDocumentController::class, 'listAvailable'])->middleware(['user.has.role:' . UserRoles::Operator->value]);
        });
    });
    

    Route::prefix('attempts')->middleware(['user.has.role:' . UserRoles::Examiner->value])
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
            Route::put('answers/{attemptAnswer}/rate', [AttemptAnswerController::class, 'rate']);
    });
    
    

    Route::post('password/change', [LoginController::class, 'changePassword'])->withoutMiddleware(['password.change']);;
    Route::inertia('password/change', 'Auth/ChangePassword')->name('password.change')->withoutMiddleware(['password.change']);;

    Route::prefix('reports')->middleware(['user.has.role:' . UserRoles::Examiner->value . ',' . UserRoles::Director->value])->group(function(){
        Route::get('frdo', [ReportController::class, "frdo"])->name('reports.frdo');
        Route::get('frdo/available', [ReportController::class, "availableFrdo"])->name('reports.frdo.available');
        Route::get('flat-table', [ReportController::class, "flatTable"])->name('reports.flat-table');

        Route::get('ministry-education/available', [ReportController::class, "availableMinistryEducationReport"])->name('reports.ministry-education.available');
        Route::get('ministry-education', [ReportController::class, "ministryEducationReport"])->name('reports.ministry-education');
    });

    Route::middleware(['user.has.role:' . UserRoles::OrgAdmin->value])->group(function (){
        Route::get('centers/{center}', [CenterController::class, "show"]);
        Route::get('centers/{center}/employees', [UserController::class, "index"]);
        Route::get('centers/{center}/addresses', [AddressController::class, "index"]);
        Route::put('centers/{center}', [CenterController::class, "update"]);
        Route::put('centers/{center}/addresses', [AddressController::class, "update"]);

        Route::patch('addresses/{address}/activity', [AddressController::class, "toggleActive"])->name('addresses.toggle.activity');
        Route::patch('addresses/{address}', [AddressController::class, "update"])->name('addresses.update');

        Route::post('addresses', [AddressController::class, "store"])->name('addresses.store');

        Route::delete('employees/{user}', [UserController::class, "destroy"])->name('users.destroy');
        Route::post('employees', [UserController::class, "store"]);
        Route::put('employees/{user}', [UserController::class, "update"]);
        Route::patch('employees/{user}/password', [LoginController::class, "resetPassword"]);
        Route::get('roles',  [UserController::class, "rolesShow"]);
    });

    Route::get('files', [FileController::class, "show"]);

    Route::prefix('instruction')->group(function(){
        Route::inertia('/exams', 'Instruction/ExamsInstruction')->name('instruction.exams');
        Route::inertia('/foreign-nationals', 'Instruction/ForeignNationalsInstruction')->name('instruction.foreign-nationals');
        Route::inertia('/centers', 'Instruction/CentersInstruction')->name('instruction.foreign-nationals')->middleware(['user.has.role:' . UserRoles::OrgAdmin->value]);
        Route::inertia('/exams/monitoring', 'Instruction/ExamMonitoringInstruction')->name('instruction.exams.monitoring')->middleware(['user.has.role:' . UserRoles::Examiner->value]);
        Route::inertia('/exams/checking', 'Instruction/ExamCheckingInstruction')->name('instruction.exams.checking')->middleware(['user.has.role:' . UserRoles::Examiner->value]);
        Route::inertia('/exams/schedule', 'Instruction/ExamScheduleInstruction')->name('instruction.exams.schedule');
    });
    
   Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function (){
    Route::inertia('login', 'Auth/Login')->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('exam-codes/verify', [ExamController::class, 'verifyCode']);
});

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
            ->can('attempt-access', 'attempt');;
    });
    
});

// Route::fallback(function () {
//     return Route::inertia('/', 'Info/Info')->name('info');
// });
