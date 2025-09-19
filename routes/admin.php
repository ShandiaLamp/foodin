<?php

use Illuminate\Support\Facades\Route;
use Shandialamp\Foodin\Http\Controllers\AuthController;

Route::prefix('/api/admin')
    ->middleware(['api'])
    ->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
    });

