<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->group(function() {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);

        // Auth Routes
        Route::middleware([AuthMiddleware::class])->group(function () {
            Route::get( '/logout', [UserController::class, 'logout']);
            Route::get('/', [UserController::class, 'getUsers']);
            Route::patch('/update/{id}', [UserController::class, 'update']);
            Route::delete('/delete/{id}', [UserController::class, 'delete']);
        });
    });

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::post('upload', [ImageController::class, 'upload']);
});