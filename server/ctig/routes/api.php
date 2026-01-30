<?php

use App\Http\Controllers\ExamCode\ExamCodeController;
use App\Http\Controllers\Login\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\ExamType\ExamTypeController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Answer\AnswerController;
use App\Http\Controllers\ExamBlock\ExamBlockController;
use App\Http\Controllers\ExamStudent\ExamStudentController;
use App\Http\Controllers\Document\DocumentController;


Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource( 'users', UserController::class); //крудные контроллеры апи

    Route::apiResource('students', StudentController::class);
        
    Route::apiResource('exams', ExamController::class);
        
    Route::apiResource('exam-types', ExamTypeController::class);

    Route::apiResource('documents', DocumentController::class);

    Route::apiResource('tasks', TaskController::class);

    Route::apiResource('answers', AnswerController::class);

    Route::apiResource('exam-blocks', ExamBlockController::class);

    Route::prefix("exam-blocks")->group(function (){
        Route::get('/{exam_block}/tests', [ExamBlockController::class, 'tests']);
    });

    Route::prefix("exam-types")->group(function (){
        Route::get('/{examType}/blocks', [ExamTypeController::class, 'blocks']);
    });

    Route::prefix("exams")->group(function (){
        Route::post('/{examId}/students', [ExamStudentController::class, "store"]);
        Route::post('/{examId}/codes', [ExamCodeController::class, "store"]);
    });
    
});

Route::post('/login', [LoginController::class, 'login']);


// use Illuminate\Support\Facades\DB;

// DB::listen(function ($query) {
//     dump([
//         'sql' => $query->sql,
//         'bindings' => $query->bindings,
//         'time' => $query->time,
//     ]);
// });