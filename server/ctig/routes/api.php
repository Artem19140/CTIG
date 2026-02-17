<?php

use App\Http\Controllers\Address\AddressController;
use App\Http\Controllers\Attempt\AttemptController;
use App\Http\Controllers\ExamCode\ExamCodeController;
use App\Http\Controllers\Login\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\ExamType\ExamTypeController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\ExamStudent\ExamStudentController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\StudentAnswer\StudentAnswerController;


Route::middleware(['auth:sanctum'])->group(function (){
    Route::apiResource( 'users', UserController::class); //крудные контроллеры апи

    Route::apiResource('students', StudentController::class);
        
    Route::apiResource('exams', ExamController::class);
        
    Route::apiResource('exam-types', ExamTypeController::class);

    Route::apiResource('documents', DocumentController::class);

    Route::apiResource('tasks', TaskController::class);

    Route::apiResource('student-answers', StudentAnswerController::class);

    Route::apiResource('addresses', AddressController::class);

    Route::prefix("exam-types")->group(function (){
        Route::get('/{examType}/blocks', [ExamTypeController::class, 'blocks']);
    });

    Route::prefix("exams")->group(function (){
        Route::post('/{exam}/students', [ExamStudentController::class, "store"]);
        Route::post('/{exam}/codes', [ExamCodeController::class, "store"]);
        Route::get('/{exam}/state', [ExamController::class, "state"]);
    });
});

Route::post('/login', [LoginController::class, 'login']);
Route::post( 'exam-codes/verify', [ExamCodeController::class, 'verify']);

Route::post('exam-attempts', [AttemptController::class, 'store'])
    ->middleware(['auth:sanctum']);//exam:prepare

Route::post( 'users', [UserController::class, 'store']);

// use Illuminate\Support\Facades\DB;

// DB::listen(function ($query) {
//     dump([
//         'sql' => $query->sql,
//         'bindings' => $query->bindings,
//         'time' => $query->time,
//     ]);
// });