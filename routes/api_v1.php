<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2026-05-05
*
* SPDX-License-Identifier: GPL-3.0-or-later
* License: Dual Licensed – GPLv3 or Commercial
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* As an alternative to GPLv3, commercial licensing is available for organizations
* or individuals requiring proprietary usage, private modifications, or support.
*
* Contact: yvsoucom@gmail.com
* GPL License: https://www.gnu.org/licenses/gpl-3.0.html
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\DomainController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Middleware\ApiTokenMiddleware;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
| NOTE:
| routes/api.php already has /api prefix automatically.
| So we ONLY use /v1 here.
*/
Route::prefix('api/v1')->group(function () {

    // =========================
    // Public endpoints
    // =========================
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show'])->whereNumber('id');

    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comments/{id}', [CommentController::class, 'show'])->whereNumber('id');

    Route::get('/domains', [DomainController::class, 'index']);
    Route::get('/domains/{id}', [DomainController::class, 'show']);

    // Auth
    Route::post('/auth/register', [UserController::class, 'register']);
    Route::post('/auth/login', [UserController::class, 'login']);

    // =========================
    // Protected endpoints
    // =========================
    Route::middleware([ApiTokenMiddleware::class])->group(function () {

        // user self
        Route::get('/auth/me', [UserController::class, 'me']);
        Route::post('/auth/logout', [UserController::class, 'logout']);

        // posts
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{id}', [PostController::class, 'update']);
        Route::patch('/posts/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);

        // comments
        Route::post('/comments', [CommentController::class, 'store']);
        Route::put('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

        // domains (admin-level usually)
        Route::post('/domains', [DomainController::class, 'store']);
        Route::put('/domains/{id}', [DomainController::class, 'update']);
        Route::delete('/domains/{id}', [DomainController::class, 'destroy']);
    });
    foreach (glob(base_path('plugins/*/routes/api.php')) as $file) {
        require $file;
    }
});