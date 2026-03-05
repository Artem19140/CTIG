<?php

use App\Http\Controllers\Web\Address\AddressController;
use App\Http\Controllers\Web\Exam\ExamController;
use App\Http\Controllers\Web\Login\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

Route::get( 'exams/{exam}', [ExamController::class, 'show']);

Route::get( 'exams', [ExamController::class, 'index']);

Route::get( 'exams', [ExamController::class, 'index'])->name('exams');

Route::post('login', [LoginController::class, 'login'])->name('login');

Route::get('/login', function () {
    return Inertia::render('Login/Login');
});

//Route::get( 'exams', [AddressController::class, 'index'])->name('addresses');

Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);
