<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::get('/hello', function () {
    return "Hello World!";
  });*/


/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//inicio
Route::controller(UserController::class)->group(function () {
  Route::post('/login', 'login');     // User login (Admin / Player)
  Route::post('/register', 'register');    
});
/*Route::post('/login', 'login');
Route::post('register', [UserController::class, 'register'])->name('register');
//Route::post('login', [UserController::class, 'login'])->name('login');*/

//register
Route::middleware('auth:api')->group(function () {
     //  Route::get('user', [UserController::class, 'user'])->name('user');
     //   Route::get('logout', [UserController::class, 'logout'])->name('logout');
        

    });




