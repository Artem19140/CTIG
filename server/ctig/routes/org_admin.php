<?php 

use App\Enums\UserRoles;
use App\Http\Controllers\Web\Address\AddressController;
use App\Http\Controllers\Web\Center\CenterController;
use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\User\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['user.has.any.role:' . UserRoles::OrgAdmin->value])->group(function (){
    Route::get('centers/{center}', [CenterController::class, "show"]);
    Route::get('centers/{center}/employees', [UserController::class, "index"]);
    Route::get('centers/{center}/addresses', [AddressController::class, "index"]);
    Route::put('centers/{center}', [CenterController::class, "update"]);
    Route::put('centers/{center}/addresses', [AddressController::class, "update"]);

    Route::patch('addresses/{address}/activity', [AddressController::class, "toggleActive"])->name('addresses.toggle.activity');
    Route::patch('addresses/{address}', [AddressController::class, "update"])->name('addresses.update');
    Route::post('addresses', [AddressController::class, "store"])->name('addresses.store');

    Route::delete('employees/{user}', [UserController::class, "destroy"])->name('users.destroy');
    Route::post('employees', [UserController::class, "store"]);
    Route::put('employees/{user}', [UserController::class, "update"]);
    Route::patch('employees/{user}/password', [LoginController::class, "resetPassword"]);

    Route::get('roles',  [UserController::class, "rolesShow"]);
});