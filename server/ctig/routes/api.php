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


Route::prefix("users")->group((function (){
    Route::get('/{user}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
}));

Route::prefix("students")->group((function (){
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);
}));

Route::prefix("exams")->group((function (){
    Route::get('/{examId}', [ExamController::class, 'show']);
    Route::get('/', [ExamController::class, 'index']); //->middleware('auth:sanctum');
    Route::post('/', [ExamController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/{examId}/students', [ExamStudentController::class, "store"]);
    Route::post('/{examId}/codes', [ExamCodeController::class, "store"]);
}));

Route::prefix("exam-types")->group((function (){
    Route::get('/{examType}', [ExamTypeController::class, 'show']);
    Route::get('/{examType}/blocks', [ExamTypeController::class, 'blocks']);
    Route::get('/', [ExamTypeController::class, 'index']);
    Route::post('/', [ExamTypeController::class, 'store']);
}));

Route::prefix("tasks")->group((function (){
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store'])->middleware('auth:sanctum');
}));

Route::prefix("answers")->group((function (){
    Route::get('/{answer}', [AnswerController::class, 'show']);
    Route::get('/', [AnswerController::class, 'index']);
    Route::post('/', [AnswerController::class, 'store']);
}));


Route::prefix("exam-blocks")->group((function (){
    Route::get('/{exam_block}', [ExamBlockController::class, 'show']);
    Route::get('/{exam_block}/tests', [ExamBlockController::class, 'tests']);
    Route::get('/', [ExamBlockController::class, 'index']);
    Route::post('/', [ExamBlockController::class, 'store']);
}));

Route::prefix("documents")->group((function (){
    Route::get('/{id}', [DocumentController::class, 'show']);
    Route::get('/', [DocumentController::class, 'index']);
    Route::post('/', [DocumentController::class, 'store']);
}));

Route::post('/login', [LoginController::class, 'login']);


use Illuminate\Support\Facades\DB;

DB::listen(function ($query) {
    dump([
        'sql' => $query->sql,
        'bindings' => $query->bindings,
        'time' => $query->time,
    ]);
});