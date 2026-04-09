<?php

use App\Http\Controllers\Web\File\FileController;
use App\Http\Controllers\Web\Info\InfoController;
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
    Route::apiResource('foreign-nationals', ForeignNationalController::class);

    // Route::get('foreign-nationals/{foreignNational}/application-forms', [ForeignNationalController::class, "getApplicationForm"])
    //         ->name('foreign-nationals.application-forms');

    Route::apiResource('enrollments', EnrollmentController::class);
    Route::prefix('enrollments')->group(function(){
        Route::post('{enrollment}/reschedule', [EnrollmentController::class, 'reschedule']);
        Route::put('{enrollment}/payment', [EnrollmentController::class, 'changePayment']);
        Route::get('{enrollment}/statements', [EnrollmentController::class, 'statement'])->name('enrollments.statements');
    });

    

    Route::prefix('exams')->group(function(){
         Route::get('available', [EnrollmentController::class, "available"]);

        Route::get('types', function(){
            return ExamType::select(['id', 'name'])->get();
        });
        Route::prefix('{exam}/documents')->group(function(){
            Route::get('codes', [ExamDocumentController::class, "codes"])->name('exam.documents.codes');
            Route::get('codes/available', [ExamDocumentController::class, "codesAvailable"]);
            Route::get('protocol', [ExamDocumentController::class, "protocol"])->name('exam.documents.protocol');
            Route::get('protocol/available', [ExamDocumentController::class, "protocolAvailable"]);
            Route::get('statement', [ExamDocumentController::class, 'statement'])->name('exam.documents.statement');
            Route::get('statement/available', [ExamDocumentController::class, 'statementAvailable']);
            Route::get('list', [ExamDocumentController::class, 'list'])->name('exam.documents.list');
            Route::get('list/available', [ExamDocumentController::class, 'listAvailable']);
        });

        Route::prefix('{exam}/foreign-nationals')->group(function(){
            Route::post('', [EnrollmentController::class, "store"]);
            Route::delete('{foreignNational}', [EnrollmentController::class, "destroy"]);
            Route::put('{foreignNational}/payment', [EnrollmentController::class, "changePayment"]);
            
        });
        
        
        Route::get('create/modal-data', [ExamController::class,'createModalData']);//->middleware('user.has.role:scheduler');
        
        Route::put('{exam}/examiners', [ExamController::class, "partialUpdate"]);
        Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exam.monitoring');
        Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
        Route::put('{exam}/monitoring/protocol-comments', [ExamMonitoringController::class, 'updateProtocolComment']);
        
        Route::get('schedule', [ExamController::class, 'schedule'])->name('exams.schedule');
        
    });
    Route::apiResource('exams', ExamController::class)->where(['exam' => '[0-9]+']);//->middleware('user.has.role:examiner1');
    
    Route::put('attempts/{attempt}/ban', [AttemptController::class, 'ban']);
    Route::get('attempts/checking', [AttemptController::class, 'toCheck'])->name('attempts.checking');
    Route::get('attempts/{attempt}/checking/tasks', [AttemptController::class, 'tasksToCheck'])->name('attempts.checking.tasks');

    Route::post('password/change', [LoginController::class, 'changePassword'])->withoutMiddleware(['password.change']);;
    Route::inertia('password/change', 'ChangePassword/ChangePassword')->name('password.change')->withoutMiddleware(['password.change']);;

    
    Route::prefix('reports')->group(function(){
        Route::get('frdo', [ReportController::class, "frdo"])->name('reports.frdo');
        Route::get('frdo/available', [ReportController::class, "available"]);
        Route::get('flat-table', [ReportController::class, "flatTable"]);
    });
    

    Route::get('centers/{center}', [CenterController::class, "show"]);
    Route::get('centers/{center}/employees', [UserController::class, "index"]);

    Route::delete('employees/{user}', [UserController::class, "destroy"]);
    Route::post('employees', [UserController::class, "store"]);

    Route::get('files', [FileController::class, "show"]);

    Route::get('roles',  [UserController::class, "rolesShow"]);

   Route::post('/logout', [LoginController::class, 'logout']);
});



Route::get('/',[InfoController::class, 'index'])->name('info');
Route::inertia('login', 'Login/Login')->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);
Route::middleware('guest')->group(function (){
    
});

Route::middleware('auth:foreignNationals')->group(function (){
    Route::prefix('exam-attempts')->group(function(){
        Route::get('{attempt}/before', [AttemptController::class, 'before'])->name('exam-attempts.before')
            ->can('attempt-access', 'attempt');

        Route::get('{attempt}', [AttemptController::class, 'current'])->name('exam-attempts') 
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}', [AttemptController::class, 'start'])
            ->can('attempt-access', 'attempt');
        
        Route::put('{attempt}/answers/{attemptAnswer}/audio/played', [AttemptAnswerController::class, 'update'])
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}/answers/{attemptAnswer}', [AttemptAnswerController::class, 'update'])
            ->can('attempt-access', 'attempt');

        Route::put('{attempt}/finish', [AttemptController::class, 'finish'])
            ->can('attempt-access', 'attempt');
    });
    
});

// Route::fallback(function () {
//     return Route::inertia('/', 'Info/Info')->name('info');
// });
