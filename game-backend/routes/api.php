<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//authentication
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/game', [App\Http\Controllers\Api\GameController::class, 'game']);
Route::get('/game/search', [App\Http\Controllers\Api\GameController::class, 'searchGames']);
Route::get('/game/{id}', [App\Http\Controllers\Api\GameController::class, 'gameById']);
Route::get('/game/{id}/recommendations', [App\Http\Controllers\Api\GameController::class, 'getRecommendations']);

Route::get('/categories', [App\Http\Controllers\Api\GameController::class, 'categories']);

Route::post('/admin/auth', [App\Http\Controllers\Api\AuthController::class, 'adminAuth']);  // Ubah urutan: api.custom dulu, baru web

//post game
Route::post('/game', [App\Http\Controllers\Api\GameController::class, 'gamePost']);

Route::middleware('auth:sanctum')->group(function(){
    //user management
    Route::get('/users', [App\Http\Controllers\Api\ManagementController::class, 'users']);
    Route::get('/user/{id}', [App\Http\Controllers\Api\ManagementController::class, 'user']);
    Route::put('/user/{id}', [App\Http\Controllers\Api\ManagementController::class, 'userUpdate']);
    Route::delete('/user/{id}', [App\Http\Controllers\Api\ManagementController::class, 'userDelete']);

    Route::post('/game/{id}/play', [App\Http\Controllers\Api\GameController::class, 'playGame']);

});
