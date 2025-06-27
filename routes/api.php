<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/jobs', [JobsController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/user/profile/{email}', [UserController::class, 'getUserProfile']);
Route::middleware('auth:sanctum')->post('/user/profile', [UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->put('/user/profile-image', [UserController::class, 'updateProfilePic']);
Route::middleware('auth:sanctum')->put('/user/workerProfile', [UserController::class, 'updateProfileWorker']);


Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/user/photos', [UserController::class, 'uploadJobPhoto']);
    Route::delete('/user/photos/{id}', [UserController::class, 'deleteJobPhoto']);
});

