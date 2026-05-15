<?php

use App\Enums\EmployeeRole;
use App\Http\Controllers\Web\Center\CenterController;
use App\Http\Controllers\Web\Statistics\SuperAdminStatisticsController;
use App\Support\AppMiddleware;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')
    ->middleware(['request.time.measure', AppMiddleware::EMPLOYEE_HAS_ANY_ROLE. ':'  . EmployeeRole::SuperAdmin->value])
    ->group(function (){
        Route::inertia('home', 'SuperAdmin/Home')->name('super-admin.home');
        Route::get('centers', [CenterController::class, "index"]);
        Route::post('centers', [CenterController::class, "store"]);
        Route::post('centers', [CenterController::class, "destroy"]);
        Route::get('statistics', [SuperAdminStatisticsController::class, 'index']);
});