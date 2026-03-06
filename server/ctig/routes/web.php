<?php

use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\Student\StudentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::resource('exams', ExamController::class)->middleware('auth');//->middleware('auth') passwordChange

Route::resource('students', StudentController::class)->middleware('auth');//->middleware('auth') passwordChange

Route::get('exams/create/modal-data', [ExamController::class,'createModalData']);

Route::post('login', [LoginController::class, 'login'])->name('login');

Route::get('password/change', function () {
    return Inertia::render('ChangePassword/ChangePassword');
})->name('password.change');

Route::post('password/change', [LoginController::class, 'changePassword']);

Route::get('/login', function () {
    return Inertia::render('Login/Login');
})->name('login');


Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);
