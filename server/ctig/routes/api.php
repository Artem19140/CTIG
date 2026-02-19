<?php

use App\Http\Controllers\Address\AddressController;
use App\Http\Controllers\Attempt\AttemptController;
use App\Http\Controllers\ExamCode\ExamCodeController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\ViolationController;
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
    Route::apiResource( 'users', UserController::class);

    Route::apiResource('students', StudentController::class);
        
    Route::apiResource('exams', ExamController::class);
        
    Route::apiResource('exam-types', ExamTypeController::class)
        ->except('destroy', 'update', 'store');

    Route::apiResource('documents', DocumentController::class);

    Route::apiResource('tasks', TaskController::class);

    Route::apiResource('student-answers', StudentAnswerController::class)
        ->except('update');

    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('attempts', AttemptController::class);

    Route::prefix("exam-types")->group(function (){
        Route::get('/{examType}/blocks', [ExamTypeController::class, 'blocks']);
    });

    Route::prefix("exams")->group(function (){
        Route::post('/{exam}/students', [ExamStudentController::class, "store"]);
        Route::post('/{exam}/codes', [ExamCodeController::class, "store"]);
        Route::get('/{exam}/state', [ExamController::class, "state"]);
    });

    Route::put('student-answers/{studentAnswer}/rate', [StudentAnswerController::class, 'rate']);
    Route::put('attempts/{attempt}/ban', [AttemptController::class, 'ban']);
    Route::get('attempts/{attempt}/speaking-tasks', [AttemptController::class, 'speaking']);
    Route::get('violations', [ViolationController::class, 'index']);//с фильтрами по exam и student
});


Route::post('/login', [LoginController::class, 'login']);
Route::post( 'exam-codes/verify', [ExamCodeController::class, 'verify']);


Route::middleware(['auth:sanctum'])->group(function (){ //во время экзамена
    Route::put('student-answers/{studentAnswer}', [StudentAnswerController::class, 'update']);
    Route::post('violations', [ViolationController::class, 'store']);
    Route::put('attempts/{attempt}/finish', [AttemptController::class, 'finish']);
}); 


Route::post('attempts', [AttemptController::class, 'store'])
    ->middleware(['auth:sanctum']);//exam:prepare только для начинания попытки




Route::post( 'users', [UserController::class, 'store']);









// use Illuminate\Support\Facades\DB;

// DB::listen(function ($query) {
//     dump([
//         'sql' => $query->sql,
//         'bindings' => $query->bindings,
//         'time' => $query->time,
//     ]);
// });