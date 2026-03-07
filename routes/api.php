<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DomainController;
use App\Http\Controllers\Api\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Isolated API v1 Routes
|--------------------------------------------------------------------------
| This API layer is intentionally isolated from legacy web controllers.
| NOTE: If your app bootstrap already prefixes /api, adjust this prefix to
| only `v1` to avoid double-prefixing.
*/
Route::prefix('api/v1')->group(function (): void {
    // Public read endpoints
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show'])->whereNumber('id');

    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comments/{id}', [CommentController::class, 'show'])->whereNumber('id');

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show'])->whereNumber('id');

    Route::get('/domains', [DomainController::class, 'index']);
    Route::get('/domains/{id}', [DomainController::class, 'show']);

    // Auth endpoints (token style)
    Route::post('/users/register', [UserController::class, 'register']);
    Route::post('/users/login', [UserController::class, 'login']);

    // Protected endpoints
    Route::middleware([ApiTokenMiddleware::class])->group(function (): void {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{id}', [PostController::class, 'update']);
        Route::patch('/posts/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);

        Route::post('/comments', [CommentController::class, 'store']);
        Route::put('/comments/{id}', [CommentController::class, 'update']);
        Route::patch('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::patch('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::post('/users/logout', [UserController::class, 'logout']);
        Route::get('/users/me', [UserController::class, 'me']);

        Route::post('/domains', [DomainController::class, 'store']);
        Route::put('/domains/{id}', [DomainController::class, 'update']);
        Route::patch('/domains/{id}', [DomainController::class, 'update']);
        Route::delete('/domains/{id}', [DomainController::class, 'destroy']);
    });
});
