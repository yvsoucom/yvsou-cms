<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\Api\Requests\LoginUserRequest;
use App\Http\Controllers\Api\Requests\StoreUserRequest;
use App\Http\Controllers\Api\Requests\UpdateUserRequest;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class UserApiTest extends TestCase
{

    private UserController $controller;
    protected function setUp(): void
    {
        parent::setUp();
        require base_path('routes/api.php');

        $this->controller = app(UserController::class);
    }

    public function test_controller_is_instantiated(): void
    {
        $this->assertInstanceOf(UserController::class, $this->controller);
    }

    public function test_store_user_validation_error_case(): void
    {
        $validator = Validator::make([], (new StoreUserRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_store_user_validation_success_case(): void
    {
        $validator = Validator::make([
            'name' => 'API User',
            'email' => 'api-user@example.com',
            'password' => 'Secret1234',
        ], (new StoreUserRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_login_validation_error_case(): void
    {
        $validator = Validator::make([], (new LoginUserRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_update_user_validation_success_case(): void
    {
        $validator = Validator::make([
            'alias_name' => 'API Alias',
            'role' => 'user',
        ], (new UpdateUserRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_public_routes_are_accessible_without_auth_middleware(): void
    {
        $indexRoute = $this->resolveRoute('GET', '/api/v1/users');
        $showRoute = $this->resolveRoute('GET', '/api/v1/users/1');
        $registerRoute = $this->resolveRoute('POST', '/api/v1/users/register');
        $loginRoute = $this->resolveRoute('POST', '/api/v1/users/login');

        $this->assertNotContains(ApiTokenMiddleware::class, $indexRoute->gatherMiddleware());
        $this->assertNotContains(ApiTokenMiddleware::class, $showRoute->gatherMiddleware());
        $this->assertNotContains(ApiTokenMiddleware::class, $registerRoute->gatherMiddleware());
        $this->assertNotContains(ApiTokenMiddleware::class, $loginRoute->gatherMiddleware());
    }

    public function test_protected_routes_require_auth_middleware(): void
    {
        $storeRoute = $this->resolveRoute('POST', '/api/v1/users');
        $updateRoute = $this->resolveRoute('PATCH', '/api/v1/users/1');
        $destroyRoute = $this->resolveRoute('DELETE', '/api/v1/users/1');
        $logoutRoute = $this->resolveRoute('POST', '/api/v1/users/logout');
        $meRoute = $this->resolveRoute('GET', '/api/v1/users/me');

        $this->assertContains(ApiTokenMiddleware::class, $storeRoute->gatherMiddleware());
        $this->assertContains(ApiTokenMiddleware::class, $updateRoute->gatherMiddleware());
        $this->assertContains(ApiTokenMiddleware::class, $destroyRoute->gatherMiddleware());
        $this->assertContains(ApiTokenMiddleware::class, $logoutRoute->gatherMiddleware());
        $this->assertContains(ApiTokenMiddleware::class, $meRoute->gatherMiddleware());
    }

    private function resolveRoute(string $method, string $uri)
    {
        $candidates = [$uri];
        if (str_starts_with($uri, '/api/')) {
            $candidates[] = '/api' . $uri;
        }

        foreach ($candidates as $candidate) {
            try {
                return Route::getRoutes()->match(Request::create($candidate, $method));
            } catch (NotFoundHttpException $e) {
                continue;
            }
        }

        $this->fail("Route {$method} {$uri} is missing");
    }
}
