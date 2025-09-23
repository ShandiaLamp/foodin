<?php

use Illuminate\Support\Facades\Route;
use Shandialamp\Foodin\Http\Controllers\AuthController;

Route::prefix('/api/admin')
    ->middleware(['api'])
    ->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::middleware('admin.jwt')->group(function () {
            Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('admin.auth.refresh');
            Route::get('/auth/user', [AuthController::class, 'user']);
            Route::get('/auth/menus', [AuthController::class, 'menus']);
        });
    });

