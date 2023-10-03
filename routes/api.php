<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');

//register
Route:: group([
    'middleware' => 'auth.api'],
    function() {
    
        Route::get('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'user'])->name('user');

    });


