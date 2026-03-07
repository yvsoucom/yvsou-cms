<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Middleware;

use App\Http\Controllers\Api\Support\ApiTokenService;
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
