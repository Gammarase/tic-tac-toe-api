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

Route::prefix('/auth')->name('auth.')->controller('AuthController')->group(
    function () {
        Route::post('/register', 'registrate')->name('register');
        Route::post('/login', 'login')->name('login');
    }
);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/user')->name('user.')->controller('UserController')->group(
        function () {
            Route::get('/', 'getUser')->name('get');
            Route::post('/', 'updateUser')->name('update');
        }
    );


    Route::prefix('/lobby')->name('lobby.')->controller('LobbyController')->group(function (){
        Route::post('/create', 'createLobby')->name('create');
    });
});
