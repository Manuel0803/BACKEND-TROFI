<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/jobs', [JobsController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/user/profile/{email}', [UserController::class, 'getUserProfile']);
Route::get('/user/profile-by-id/{id}', [UserController::class, 'getUserProfileById']);
Route::get('/user/photos/{id}', [UserController::class, 'getUserPhotos']);

Route::get('/workers', [UserController::class, 'getAllWorkers']);
Route::get('/workers/search', [UserController::class, 'searchWorkers']);

Route::put('/user/update-name', [UserController::class, 'updateName']);
Route::put('/user/update-userDescription', [UserController::class, 'updateUserDescription']);
Route::put('/user/update-job_description', [UserController::class, 'updateJobDescription']);

Route::middleware('auth:sanctum')->post('/reviews', [ReviewController::class, 'createReview']);
Route::get('/user-reviews/{userId}', [ReviewController::class, 'getReviewsByUser']);

Route::middleware('auth:sanctum')->post('/user/profile', [UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->put('/user/profile-image', [UserController::class, 'updateProfilePic']);
Route::middleware('auth:sanctum')->put('/user/workerProfile', [UserController::class, 'updateProfileWorker']);


Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/user/photos', [UserController::class, 'uploadJobPhoto']);
    Route::delete('/user/photos/{id}', [UserController::class, 'deleteJobPhoto']);
});

