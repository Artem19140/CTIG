<?php

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\PasswordController;
use App\Http\Controllers\Web\Enrollment\EnrollmentDocumentController;
use App\Http\Controllers\Web\File\FileController;
use App\Http\Controllers\Web\ForeignNational\ForeignNationalExportController;
use App\Http\Controllers\Web\Statistics\StatisticsController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Enrollment\EnrollmentController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Report\ReportController;
use App\Http\Controllers\Web\ForeignNational\ForeignNationalController;
use App\Http\RedirectResolver;
use Illuminate\Support\Facades\Route;

Route::middleware(['log.context','auth', 'auth.session', 'user.active', 'center.active', 'password.change'])->group(function(){

    Route::apiResource('foreign-nationals', ForeignNationalController::class)
        ->where(['foreign_national' => '[0-9]+'])
        ->middlewareFor(['store', 'update'], ['user.has.any.role:' . UserRoles::implode(UserRoles::Operator)])
        ->middlewareFor(['show', 'index'],  ['user.has.any.role:' . UserRoles::implode(UserRoles::Operator, UserRoles::Director)])
        ->middlewareFor(['show'],  ['user.has.any.role:' . UserRoles::implode(UserRoles::Operator, UserRoles::Examiner, UserRoles::Director)])
        ->except(['destroy']);


    Route::apiResource('enrollments', EnrollmentController::class)
        ->where(['enrollment' => '[0-9]+'])
        ->middleware(['user.has.any.role:' . UserRoles::implode(UserRoles::Operator)]);
    
    Route::put('enrollments/{enrollment}/payment', [EnrollmentController::class, 'changePayment'])
        ->middleware(['user.has.any.role:' . UserRoles::implode(UserRoles::Operator, UserRoles::Examiner)])
        ->name('enrollments.change.payment');

    Route::get('enrollments/{enrollment}/statements', [EnrollmentDocumentController::class, 'statement'])
        ->middleware(['user.has.any.role:' . UserRoles::implode(UserRoles::Operator)])
        ->name('enrollments.statements');

    Route::middleware(['user.has.any.role:' . UserRoles::implode(UserRoles::Director)])->group(function() {
        Route::get('foreign-nationals/export', [ForeignNationalExportController::class, 'export'])->name('foreign-nationals.export');
        Route::get('foreign-nationals/export/available', [ForeignNationalExportController::class, 'exportAvailable']);
        Route::get('statistics', [StatisticsController::class, 'index']);
    });
    
    require __DIR__.'/exams.php';
    
    require __DIR__.'/attempts.php';
    
    Route::prefix('reports')->group(function(){

        Route::middleware('user.has.any.role:' . UserRoles::implode(UserRoles::Director, UserRoles::Operator))
        ->group(function (){
            Route::get('frdo', [ReportController::class, "frdo"])->name('reports.frdo');
            Route::get('frdo/available', [ReportController::class, "availableFrdo"])->name('reports.frdo.available');
        });
        
        Route::middleware(['user.has.any.role:' . UserRoles::implode(UserRoles::Director)])
        ->group(function (){
            Route::get('flat-table', [ReportController::class, "flatTable"])->name('reports.flat-table');

            Route::get('ministry-education/available', [ReportController::class, "availableMinistryEducationReport"])->name('reports.ministry-education.available');
            Route::get('ministry-education', [ReportController::class, "ministryEducationReport"])->name('reports.ministry-education');
        });
        
    });

    require __DIR__.'/org_admin.php';

    Route::prefix('instruction')->group(function(){
        Route::inertia('/exams', 'Instruction/ExamsInstruction')
            ->name('instruction.exams');

        Route::inertia('/foreign-nationals', 'Instruction/ForeignNationalsInstruction')
            ->name('instruction.foreign-nationals');

        Route::inertia('/centers', 'Instruction/CentersInstruction')
            ->name('instruction.foreign-nationals')
            ->middleware(['user.has.any.role:' . UserRoles::OrgAdmin->value]);

        Route::inertia('/exams/monitoring', 'Instruction/ExamMonitoringInstruction')
            ->name('instruction.exams.monitoring')
            ->middleware(['user.has.any.role:' . UserRoles::Examiner->value]);

        Route::inertia('/exams/checking', 'Instruction/ExamCheckingInstruction')
            ->name('instruction.exams.checking')
            ->middleware(['user.has.any.role:' . UserRoles::Examiner->value]);

        Route::inertia('/exams/schedule', 'Instruction/ExamScheduleInstruction')
            ->name('instruction.exams.schedule');
    });

    Route::post('password/change', [PasswordController::class, 'change'])->withoutMiddleware(['password.change']);
    Route::inertia('password/change', 'Auth/ChangePassword')->name('password.change')->withoutMiddleware(['password.change']);

    Route::get('files', [FileController::class, "show"])
        ->middleware('user.has.any.role:' . UserRoles::implode(
            UserRoles::Operator, 
            UserRoles::Examiner, 
            UserRoles::Director
        ));

    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
    Route::post('logout/all', [LogoutController::class, 'logoutAll'])->name('logout.all');
});

Route::middleware(['log.context','guest:web,foreignNationals'])->group(function (){
    Route::inertia('login', 'Auth/Login')->name('login');  
    Route::post('login', [LoginController::class, 'login']);
    Route::post('exam-codes/verify', [ExamController::class, 'verifyCode']);
    Route::inertia('attempts/finish', 'Attempt/AfterAttempt')->name('attempts.finish');
});

Route::get('me', function(RedirectResolver $resolver){
    return $resolver->execute();
})->name('me'); 

require __DIR__.'/foreign_national.php';