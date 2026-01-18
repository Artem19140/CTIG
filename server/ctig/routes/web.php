<!-- <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MigrantController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [MigrantController::class, 'show']);

Route::post('/home', [MigrantController::class, 'store']);

//Route::post('/users', [UserController::class, 'store']); -->