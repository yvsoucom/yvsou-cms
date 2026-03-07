<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ApiResponse;
use App\Http\Controllers\Api\Requests\LoginUserRequest;
use App\Http\Controllers\Api\Requests\StoreUserRequest;
use App\Http\Controllers\Api\Requests\UpdateUserRequest;
use App\Http\Controllers\Api\Support\ApiTokenService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly ApiTokenService $tokenService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $users = User::query()->paginate(20);
            return $this->success($users, 'Users fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch users', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);
            if ($user === null) {
                return $this->error('User not found', ['id' => $id], 404);
            }

            return $this->success($user, 'User fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch user', ['exception' => $e->getMessage()], 500);
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

            return $this->success($user, 'User created', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create user', ['exception' => $e->getMessage()], 500);
        }
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $user = User::query()->where('email', $credentials['email'])->first();

            if ($user === null || !Hash::check($credentials['password'], $user->password)) {
                return $this->error('Invalid credentials', ['email' => ['Authentication failed']], 401);
            }

            $token = $this->tokenService->issue($user);

            return $this->success([
                'token_type' => 'Bearer',
                'access_token' => $token,
                'user' => $user,
            ], 'Login successful');
        } catch (Throwable $e) {
            return $this->error('Failed to login', ['exception' => $e->getMessage()], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $token = (string) request()->bearerToken();
            if ($token !== '') {
                $this->tokenService->revoke($token);
            }

            return $this->success(null, 'Logout successful');
        } catch (Throwable $e) {
            return $this->error('Failed to logout', ['exception' => $e->getMessage()], 500);
        }
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();
        if ($user === null) {
            return $this->error('Unauthorized', ['auth' => ['No authenticated user']], 401);
        }

        return $this->success($user, 'Current user fetched');
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);
            if ($user === null) {
                return $this->error('User not found', ['id' => $id], 404);
            }

            $payload = $request->validated();
            if (isset($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']);
            }

            $user->fill($payload);
            $user->save();

            return $this->success($user->fresh(), 'User updated');
        } catch (Throwable $e) {
            return $this->error('Failed to update user', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);
            if ($user === null) {
                return $this->error('User not found', ['id' => $id], 404);
            }

            $user->delete();
            return $this->success(['id' => $id], 'User deleted');
        } catch (Throwable $e) {
            return $this->error('Failed to delete user', ['exception' => $e->getMessage()], 500);
        }
    }
}
