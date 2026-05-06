<?php

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Center\CenterController;
use Illuminate\Support\Facades\Route;


Route::middleware(['user.has.any.role:' . UserRoles::SuperAdmin->value])->group(function (){
    Route::get('centers', [CenterController::class, "index"]);
    Route::post('centers', [CenterController::class, "store"]);
    Route::post('centers', [CenterController::class, "destroy"]);
});