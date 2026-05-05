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

namespace App\Http\Controllers\Api\v1;

use App\Support\Api\ApiResponse;

use App\Http\Requests\Api\V1\LoginUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Controllers\Api\V1\Support\ApiTokenService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    

    public function __construct(private readonly ApiTokenService $tokenService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $users = User::query()->paginate(20);
            return ApiResponse::success($users, 'Users fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch users', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);
            if ($user === null) {
                return ApiResponse::error('User not found', ['id' => $id], 404);
            }

            return ApiResponse::success($user, 'User fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch user', ['exception' => $e->getMessage()], 500);
        }
    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $payload = $request->validated();
            $payload['password'] = Hash::make($payload['password']);
            $user = User::query()->create($payload);

            return ApiResponse::success($user, 'User created', 201);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to create user', ['exception' => $e->getMessage()], 500);
        }
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $user = User::query()->where('email', $credentials['email'])->first();

            if ($user === null || !Hash::check($credentials['password'], $user->password)) {
                return ApiResponse::error('Invalid credentials', ['email' => ['Authentication failed']], 401);
            }
 
            $token = $this->tokenService->issue(
                $user,
                'admin',
                ['users.read', 'users.write'],
                'web-login'
            );

            return ApiResponse::success([
                'token_type' => 'Bearer',
                'access_token' => $token,
                'user' => $user,
            ], 'Login successful');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to login', ['exception' => $e->getMessage()], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $token = (string) request()->bearerToken();
            if ($token !== '') {
                $this->tokenService->revoke($token);
            }

            return ApiResponse::success(null, 'Logout successful');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to logout', ['exception' => $e->getMessage()], 500);
        }
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();
        if ($user === null) {
            return ApiResponse::error('Unauthorized', ['auth' => ['No authenticated user']], 401);
        }

        return ApiResponse::success($user, 'Current user fetched');
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);
            if ($user === null) {
                return ApiResponse::error('User not found', ['id' => $id], 404);
            }

            $payload = $request->validated();
            if (isset($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']);
            }

            $user->fill($payload);
            $user->save();

            return ApiResponse::success($user->fresh(), 'User updated');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to update user', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);
            if ($user === null) {
                return ApiResponse::error('User not found', ['id' => $id], 404);
            }

            $user->delete();
            return ApiResponse::success(['id' => $id], 'User deleted');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to delete user', ['exception' => $e->getMessage()], 500);
        }
    }
}
