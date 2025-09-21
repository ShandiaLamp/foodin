<?php

use Illuminate\Support\Facades\Route;
use Shandialamp\Foodin\Http\Controllers\AuthController;

Route::prefix('/api/admin')
    ->middleware(['api'])
    ->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::middleware('admin.jwt')->group(function () {
            Route::get('/auth/user', [AuthController::class, 'user']);
            Route::get('/auth/menus', [AuthController::class, 'menus']);
        });
    });

