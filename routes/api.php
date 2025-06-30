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

// Públicas
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

// Endpoints de actualización agrupados por autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    // Reseñas
    Route::post('/reviews', [ReviewController::class, 'createReview']);

    // Perfil de usuario
    Route::post('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/profile-image', [UserController::class, 'updateProfilePic']);
    Route::put('/user/workerProfile', [UserController::class, 'updateProfileWorker']);

    // Edición de campos individuales
    Route::put('/user/update-name', [UserController::class, 'updateName']);
    Route::put('/user/update-userDescription', [UserController::class, 'updateUserDescription']);
    Route::put('/user/update-job_description', [UserController::class, 'updateJobDescription']);
    Route::put('/user/update-location', [UserController::class, 'updateLocation']);
    Route::put('/user/update-phone', [UserController::class, 'updatePhoneNumber']);

    // Fotos de trabajo
    Route::post('/user/photos', [UserController::class, 'uploadJobPhoto']);
    Route::delete('/user/photos/{id}', [UserController::class, 'deleteJobPhoto']);
});

