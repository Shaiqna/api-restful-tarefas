<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/task/{id}', [TaskController::class, 'show']);
    Route::post('/create/task', [TaskController::class, 'store']);
    Route::put('/update/task/{id}', [TaskController::class, 'update']);
    Route::delete('/delete/task/{id}', [TaskController::class, 'destroy']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
