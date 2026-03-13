<?php

use App\Http\Controllers\Web\Attempt\AttemptController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\Report\ReportController;
use App\Http\Controllers\Web\Student\StudentController;
use App\Http\Controllers\Web\StudentAnswer\StudentAnswerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('exams/available', [ExamController::class, "available"]);


Route::middleware(['auth', 'user.is.work', 'organization.is.work', 'password.change'])->group(function(){
    Route::resource('students', StudentController::class)->middleware('auth');//->middleware('auth') password.change
    Route::get('students/{student}/application-forms', [StudentController::class, "getApplicationForm"]);
    
    Route::prefix('exams')->group(function(){
        Route::get('{exam}/codes', [ExamController::class, "formCodes"]);
        Route::get('create/modal-data', [ExamController::class,'createModalData']);
        Route::post('{exam}/student', [ExamController::class, "enroll"]);
        Route::inertia('monitoring', 'ExamMonitoring/ExamMonitoring')->name('exam.monitoring');
    });
    Route::resource('exams', ExamController::class)->where(['exam' => '[0-9]+']);
    
    
    Route::post('password/change', [LoginController::class, 'changePassword'])->withoutMiddleware(['password.change']);;
    Route::inertia('password/change', 'ChangePassword/ChangePassword')->name('password.change')->withoutMiddleware(['password.change']);;

    Route::inertia('attempts/checking', 'AttemptsChecking/AttemptsChecking')->name('attempts.checking');
    Route::post('/logout', [LoginController::class, 'logout']);
    
    Route::get('reports/frdo', [ReportController::class, "frdo"]);
    Route::get('reports/frdo/available', [ReportController::class, "available"]);
});




Route::middleware('guest')->group(function (){
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::inertia('login', 'Login/Login')->name('login');
    Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);
});

Route::middleware('auth:students')->group(function (){
    Route::get('exam-attempts/before', [ExamController::class, 'before'])->name('exam-attempts.before');
    Route::put('exam-attempts', [AttemptController::class, 'start']);
    Route::put('student-answers/{studentAnswer}/', [StudentAnswerController::class, 'update']);
    Route::get('exam-attempts', [AttemptController::class, 'current'])->name('exam-attempts');
});
