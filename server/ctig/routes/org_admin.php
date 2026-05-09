<?php 

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Address\AddressController;
use App\Http\Controllers\Web\Center\CenterController;
use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\User\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['user.has.any.role:' . UserRoles::OrgAdmin->value])->group(function (){
    Route::get('centers/{center}', [CenterController::class, "show"])->name('centers.show');
    Route::get('centers/{center}/employees', [UserController::class, "index"]);
    Route::get('centers/{center}/addresses', [AddressController::class, "index"]);
    Route::put('centers/{center}', [CenterController::class, "update"]);
    Route::put('centers/{center}/addresses', [AddressController::class, "update"]);

    Route::delete('centers/{center}/addresses/{address}', [AddressController::class, "destroy"])->name('centers.addresses.destroy');
    Route::patch('centers/{center}/addresses/{address}', [AddressController::class, "update"])->name('centers.addresses.update');
    Route::post('centers/{center}/addresses', [AddressController::class, "store"])->name('centers.addresses.store');

    Route::delete('employees/{user}', [UserController::class, "destroy"])->name('users.destroy');
    Route::post('employees', [UserController::class, "store"]);
    Route::put('employees/{user}', [UserController::class, "update"]);
    Route::patch('employees/{user}/password', [LoginController::class, "resetPassword"]);

    Route::get('roles',  [UserController::class, "rolesShow"]);
});