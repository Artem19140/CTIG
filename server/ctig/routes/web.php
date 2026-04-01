<?php

use App\Http\Controllers\Web\File\FileController;
use App\Http\Controllers\Web\User\UserController;
use App\Http\Controllers\Web\Attempt\AttemptController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Exam\ExamDocumentController;
use App\Http\Controllers\Web\Exam\ExamEnrollmentController;
use App\Http\Controllers\Web\Exam\ExamMonitoringController;
use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\Organization\OrganizationController;
use App\Http\Controllers\Web\Report\ReportController;
use App\Http\Controllers\Web\ForeignNational\ForeignNationalController;
use App\Http\Controllers\Web\AttemptAnswer\AttemptAnswerController;
use App\Models\ExamType;
use Illuminate\Support\Facades\Route;





Route::middleware(['auth', 'user.is.active', 'organization.is.active', 'password.change'])->group(function(){
    Route::resource('foreign-nationals', ForeignNationalController::class);

    Route::get('foreign-nationals/{foreignNational}/application-forms', [ForeignNationalController::class, "getApplicationForm"])
            ->name('foreign-nationals.application-forms');
        //->middleware('user.has.role:operator');
        
    Route::post('foreign-nationals/{foreignNational}/exams/transfer', [ExamEnrollmentController::class, "transfer"]);

    Route::prefix('exams/')->group(function(){
        Route::prefix('{exam}/documents')->group(function(){
            Route::get('codes', [ExamDocumentController::class, "codes"])->name('exam.documents.codes');
            Route::get('codes/available', [ExamDocumentController::class, "codesAvailable"]);
            Route::get('protocol', [ExamDocumentController::class, "protocol"])->name('exam.documents.protocol');
            Route::get('protocol/available', [ExamDocumentController::class, "protocolAvailable"]);
            Route::get('statement', [ExamDocumentController::class, 'statement'])->name('exam.documents.statement');
            Route::get('statement/available', [ExamDocumentController::class, 'statementAvailable']);
        });

        Route::prefix('{exam}/foreign-nationals')->group(function(){
            Route::post('foreign-nationals', [ExamEnrollmentController::class, "store"]);
            Route::delete('foreign-nationals/{foreignNational}', [ExamEnrollmentController::class, "destroy"]);
            Route::put('foreign-nationals/{foreignNational}/payment', [ExamEnrollmentController::class, "changePayment"]);
            Route::get('foreign-nationals/list', [ExamDocumentController::class, 'foreignNationalsList']);
        });
        
        
        Route::get('create/modal-data', [ExamController::class,'createModalData']);//->middleware('user.has.role:scheduler');
        
        Route::put('{exam}/examiners', [ExamController::class, "partialUpdate"]);
        Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exam.monitoring');
        Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
        Route::put('{exam}/monitoring/protocol-comments', [ExamMonitoringController::class, 'updateProtocolComment']);
        
        Route::get('schedule', [ExamController::class, 'schedule'])->name('exams.schedule');
        
    });
    Route::resource('exams', ExamController::class)->where(['exam' => '[0-9]+']);//->middleware('user.has.role:examiner1');
    
    Route::put('attempts/{attempt}/ban', [AttemptController::class, 'ban']);
    Route::get('attempts/checking', [AttemptController::class, 'toCheck'])->name('attempts.checking');

    Route::get('attempts/{attempt}/checking/tasks', [AttemptController::class, 'tasksToCheck'])->name('attempts.checking.tasks');

    Route::post('password/change', [LoginController::class, 'changePassword'])->withoutMiddleware(['password.change']);;
    Route::inertia('password/change', 'ChangePassword/ChangePassword')->name('password.change')->withoutMiddleware(['password.change']);;

    Route::post('/logout', [LoginController::class, 'logout']);
    
    Route::get('reports/frdo', [ReportController::class, "frdo"])->name('reports.frdo');
    Route::get('reports/frdo/available', [ReportController::class, "available"]);
    Route::get('reports/flat-table', [ReportController::class, "flatTable"]);

    Route::get('organizations/{organization}', [OrganizationController::class, "show"]);
    Route::get('organizations/{organization}/employees', [UserController::class, "index"]);
    Route::delete('employees/{user}', [UserController::class, "destroy"]);
    Route::post('employees', [UserController::class, "store"]);

    Route::get('files', [FileController::class, "show"]);

    Route::get('roles',  [UserController::class, "rolesShow"]);

    Route::get('exams/available', [ExamEnrollmentController::class, "available"]);

    Route::get('exams/types', function(){
        return ExamType::select(['id', 'name'])->get();
    });
});




Route::middleware('guest')->group(function (){
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::inertia('login', 'Login/Login')->name('login');
    Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);
});

Route::middleware('auth:foreignNationals')->group(function (){
    Route::get('exam-attempts/{attempt}/before', [AttemptController::class, 'before'])->name('exam-attempts.before')
        ->can('attempt-access', 'attempt');
    Route::put('exam-attempts/{attempt}', [AttemptController::class, 'start'])
        ->can('attempt-access', 'attempt');
    Route::put('exam-attempts/{attempt}/answers', [AttemptAnswerController::class, 'update'])
        ->can('attempt-access', 'attempt');
    Route::get('exam-attempts/{attempt}', [AttemptController::class, 'current'])->name('exam-attempts')
        ->can('attempt-access', 'attempt');
    Route::put('exam-attempts/{attempt}/finish', [AttemptController::class, 'finish'])
        ->can('attempt-access', 'attempt');
});
