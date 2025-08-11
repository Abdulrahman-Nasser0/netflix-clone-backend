<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::post('register', [UserController::class,'register']);
Route::post('login', [UserController::class,'login'])->name('login');
Route::post('logout', [UserController::class,'logout'])->middleware('auth:sanctum');
Route::put('user', [UserController::class,'user'])->middleware('auth:sanctum');
Route::post('user/avatar', [UserController::class,'avatar'])->middleware('auth:sanctum');

Route::post('/add', [FavoriteController::class, 'add'])->middleware('auth:sanctum');
Route::get('/retrieve', [FavoriteController::class, 'retrieve'])->middleware('auth:sanctum');
Route::delete('/delete/{tmdb_id}', [FavoriteController::class, 'delete'])->middleware('auth:sanctum');
