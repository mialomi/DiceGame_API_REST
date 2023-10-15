<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GameController;


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


Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');

//register
Route::middleware('auth:api, scope:admin,player')->group(function () { //, scope:admin,player


       Route::get('user', [UserController::class, 'user'])->name('user');
       Route::put('user/{id}', [UserController::class, 'update'])->name('update');
       Route::get('logout', [UserController::class, 'logout'])->name('logout');

    });

Route::middleware('auth:api, scope:player')->group(function () {

      Route::post('/players/{id}/games/', [GameController::class, 'diceRoll']); //un jugador/a específic realitza una tirada dels daus.
      Route::get('/players/{id}/games', [GameController::class, 'show_player_rolls']); //retorna el llistat de jugades per un jugador/a i % exit
      Route::delete('/players/{id}/games', [GameController::class, 'delete_players_list']); //elimina les tirades del jugador/a.

});

Route::middleware('auth:api, scope:admin')->group(function () {

      Route::get('/players', [UserController::class, 'list_players']); // retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits
      Route::get('/players/ranking', [UserController::class, 'ranking_players']); //retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
      Route::get('/players/ranking/winner', [UserController::class, 'ranking_winner']); //retorna el jugador/a amb millor percentatge d’èxit.
      Route::get('/players/ranking/loser', [UserController::class, 'ranking_loser']); //retorna el jugador/a amb pitjor percentatge d’èxit.

});




