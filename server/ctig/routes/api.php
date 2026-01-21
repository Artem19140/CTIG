<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Migrant\MigrantController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\ExamType\ExamTypeController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Answer\AnswerController;
use App\Http\Controllers\ExamBlock\ExamBlockController;


Route::prefix("users")->group((function (){
    Route::get('/{user}', [UserController::class, 'show'])
    ->whereNumber('user');
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
}));

Route::prefix("migrants")->group((function (){
    Route::get('/{migrant}', [MigrantController::class, 'show'])
    ->whereNumber('migrant');
    Route::get('/', [MigrantController::class, 'index']);
    Route::post('/', [MigrantController::class, 'store']);
}));

Route::prefix("exams")->group((function (){
    Route::get('/{exam}', [ExamController::class, 'show'])
    ->whereNumber('exam');
    Route::get('/', [ExamController::class, 'index']);
    Route::get('/', [ExamController::class, 'index']);
    Route::post('/', [ExamController::class, 'store']);
}));

Route::prefix("exam-types")->group((function (){
    Route::get('/{exam_type}', [ExamTypeController::class, 'show'])
    ->whereNumber('exam_type');
    Route::get('/{exam_type}/blocks', [ExamTypeController::class, 'blocks'])
    ->whereNumber('exam_type');
    Route::get('/', [ExamTypeController::class, 'index']);
    Route::post('/', [ExamTypeController::class, 'store']);
}));

Route::prefix("questions")->group((function (){
    Route::get('/{question}', [QuestionController::class, 'show'])
    ->whereNumber('question');
    Route::get('/', [QuestionController::class, 'index']);
    Route::post('/', [QuestionController::class, 'store']);
}));

Route::prefix("answers")->group((function (){
    Route::get('/{answer}', [AnswerController::class, 'show'])
    ->whereNumber('answer');
    Route::get('/', [AnswerController::class, 'index']);
    Route::post('/', [AnswerController::class, 'store']);
}));


Route::prefix("exam-blocks")->group((function (){
    Route::get('/{exam_block}', [ExamBlockController::class, 'show'])
    ->whereNumber('exam_block');
    Route::get('/{exam_block}/tests', [ExamBlockController::class, 'tests'])
    ->whereNumber('exam_block');
    Route::get('/', [ExamBlockController::class, 'index']);
    Route::post('/', [ExamBlockController::class, 'store']);
}));