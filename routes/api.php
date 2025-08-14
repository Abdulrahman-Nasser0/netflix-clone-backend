<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('user', [UserController::class,'user'])->middleware('auth:sanctum');
Route::post('register', [UserController::class,'register']);
Route::post('login', [UserController::class,'login'])->name('login');
Route::post('logout', [UserController::class,'logout'])->middleware('auth:sanctum');
Route::put('userUpdate', [UserController::class,'userUpdate'])->middleware('auth:sanctum');

Route::post('/add', [FavoriteController::class, 'add'])->middleware('auth:sanctum');
Route::get('/retrieve', [FavoriteController::class, 'retrieve'])->middleware('auth:sanctum');
Route::delete('/delete/{tmdb_id}', [FavoriteController::class, 'delete'])->middleware('auth:sanctum');
