<?php 

use App\Enums\EmployeeRole;
use App\Http\Controllers\Web\Address\AddressController;
use App\Http\Controllers\Web\Center\CenterController;
use App\Http\Controllers\Web\Auth\PasswordController;
use App\Http\Controllers\Web\Employee\EmployeeController;
use App\Support\AppMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([AppMiddleware::EMPLOYEE_HAS_ANY_ROLE. ':'  . EmployeeRole::implode(EmployeeRole::CenterAdmin)])->group(function (){
    Route::get('centers/{center}', [CenterController::class, "show"])->name('centers.show');
    Route::get('centers/{center}/employees', [EmployeeController::class, "index"]);
    Route::get('centers/{center}/addresses', [AddressController::class, "index"]);
    Route::put('centers/{center}', [CenterController::class, "update"]);
    Route::put('centers/{center}/addresses', [AddressController::class, "update"]);

    Route::delete('centers/{center}/addresses/{address}', [AddressController::class, "destroy"])->name('centers.addresses.destroy');
    Route::patch('centers/{center}/addresses/{address}', [AddressController::class, "update"])->name('centers.addresses.update');
    Route::post('centers/{center}/addresses', [AddressController::class, "store"])->name('centers.addresses.store');

    Route::delete('employees/{employee}', [EmployeeController::class, "destroy"])->name('employees.destroy');
    Route::post('employees', [EmployeeController::class, "store"]);
    Route::put('employees/{employee}', [EmployeeController::class, "update"]);
    Route::patch('employees/{employee}/password', [PasswordController::class, "reset"]);

    Route::get('roles',  [EmployeeController::class, "rolesShow"]);

    
});

Route::inertia('clothes',  'SuperAdmin/Clothes/Clothes');