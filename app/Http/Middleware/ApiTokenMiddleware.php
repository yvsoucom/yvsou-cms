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


declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\Api\V1\Support\ApiTokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenMiddleware
{
    public function __construct(private readonly ApiTokenService $tokenService)
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        $token = (string) $request->bearerToken();
        if ($token === '') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
                'error' => ['token' => ['Missing bearer token']],
            ], 401);
        }

        $user = $this->tokenService->resolveUser($token);
        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null,
                'error' => ['token' => ['Invalid or expired token']],
            ], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
