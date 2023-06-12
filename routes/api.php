<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthentificationController;

Route::get('/posts', [PostController::class, 'index'])->middleware('auth:sanctum');
Route::get('/posts/{id}', [PostController::class, 'show'])->middleware(['auth:sanctum']);
Route::get('/posts2/{id}', [PostController::class, 'show2']);

Route::post('/login', [AuthentificationController::class, 'login']);
Route::post('/logout', [AuthentificationController::class, 'logout'])->middleware('auth:sanctum');



