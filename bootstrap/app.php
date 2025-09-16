<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Http\Middleware\PreventReinstall;
use Illuminate\Routing\Router;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;



$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register global middleware
        
        $middleware->append(EncryptCookies::class);
        $middleware->append(AddQueuedCookiesToResponse::class);
        $middleware->append(StartSession::class);
        $middleware->append(\App\Http\Middleware\SetLocale::class);
    
        // ✅ Route middleware (used by name in routes)
    
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'prevent.reinstall' => PreventReinstall::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling
    
    })->create();
// 👇 Register route middleware alias
app()->booted(function () {
    // AppServiceProvider.php

    config(['app.pro' => file_exists(base_path('pro.version'))]);
});


return $app;