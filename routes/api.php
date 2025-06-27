<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrabajosController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('trabajos', [TrabajosController::class, 'index']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);




Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/logout', [AuthController::class, 'logout']);
});