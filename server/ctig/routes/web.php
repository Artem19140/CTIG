<?php

use App\Enums\UserRoles;
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
use App\Http\Controllers\Web\Student\StudentController;
use App\Http\Controllers\Web\AttemptAnswer\AttemptAnswerController;
use App\Http\Resources\Role\RoleResource;
use App\Models\ExamType;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




Route::middleware(['auth', 'user.is.active', 'organization.is.active', 'password.change'])->group(function(){
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/application-forms', [StudentController::class, "getApplicationForm"])
            ->name('students.application-forms');
        //->middleware('user.has.role:operator');
    
    Route::prefix('exams')->group(function(){
        Route::get('{exam}/codes', [ExamController::class, "formCodes"]);//->middleware('user.has.role:examiner');
        Route::get('create/modal-data', [ExamController::class,'createModalData']);//->middleware('user.has.role:scheduler');
        Route::post('{exam}/students', [ExamEnrollmentController::class, "store"]);
        Route::delete('{exam}/students/{student}', [ExamEnrollmentController::class, "destroy"]);
        Route::post('{exam}/students/{student}', [ExamEnrollmentController::class, "transfer"]);
        Route::get('monitoring', [ExamMonitoringController::class, 'index'])->name('exam.monitoring');
        Route::get('{exam}/monitoring', [ExamMonitoringController::class, 'show']);
        Route::get('{exam}/students/list', [ExamDocumentController::class, 'studentsList']);
        Route::get('schedule', [ExamController::class, 'schedule'])->name('exams.schedule');
        Route::get('{exam}/statement', [ReportController::class, 'statement']);
    });
    Route::resource('exams', ExamController::class)->where(['exam' => '[0-9]+']);
    
    Route::put('attempts/{attempt}/ban', [AttemptController::class, 'ban']);
    Route::get('attempts/checking', [AttemptController::class, 'toCheck'])->name('attempts.checking');

    Route::get('attempts/{attempt}/checking/tasks', [AttemptController::class, 'tasksToCheck'])->name('attempts.checking.tasks');

    Route::post('password/change', [LoginController::class, 'changePassword'])->withoutMiddleware(['password.change']);;
    Route::inertia('password/change', 'ChangePassword/ChangePassword')->name('password.change')->withoutMiddleware(['password.change']);;

    Route::post('/logout', [LoginController::class, 'logout']);
    
    Route::get('reports/frdo', [ReportController::class, "frdo"]);
    Route::get('reports/frdo/available', [ReportController::class, "available"]);
    Route::get('reports/flat-table', [ReportController::class, "flatTable"]);

    Route::get('organizations/{organization}', [OrganizationController::class, "show"]);
    Route::get('organizations/{organization}/employees', [UserController::class, "index"]);
    Route::delete('employees/{user}', [UserController::class, "destroy"]);
    Route::post('employees', [UserController::class, "store"]);

    Route::get('files', [FileController::class, "show"]);

    Route::get('roles',  [UserController::class, "rolesShow"]);

    Route::get('exams/available', [ExamController::class, "available"]);

    Route::get('exams/types', function(){
        return ExamType::select(['id', 'name'])->get();
    });
});




Route::middleware('guest')->group(function (){
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::inertia('login', 'Login/Login')->name('login');
    Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);
});

Route::middleware('auth:students')->group(function (){
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
