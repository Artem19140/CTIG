<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MigrantController;
use App\Http\Controllers\UserController;


Route::prefix("users")->group((function (){
    Route::get('/', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
}));

Route::prefix("migrants")->group((function (){
    Route::get('/', [MigrantController::class, 'show']);
    Route::post('/', [MigrantController::class, 'store']);
}));