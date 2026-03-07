<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\Api\Support\ApiTokenService;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiTokenMiddlewareTest extends TestCase
{

    private ApiTokenService $tokenService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = app(ApiTokenService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_authentication_fails_without_bearer_token(): void
    {
        $middleware = new ApiTokenMiddleware($this->tokenService);
        $request = Request::create('/api/v1/posts', 'POST');

        $response = $middleware->handle($request, fn () => response()->json(['ok' => true]));

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_authentication_fails_with_invalid_token(): void
    {
        $middleware = new ApiTokenMiddleware($this->tokenService);
        $request = Request::create('/api/v1/posts', 'POST', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer invalid-token',
        ]);

        $response = $middleware->handle($request, fn () => response()->json(['ok' => true]));

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_authentication_passes_with_valid_token(): void
    {
        $tokenService = Mockery::mock(ApiTokenService::class);
        $user = new User();
        $user->id = 123;

        $tokenService->shouldReceive('resolveUser')
            ->once()
            ->with('valid-token')
            ->andReturn($user);

        $middleware = new ApiTokenMiddleware($tokenService);
        $request = Request::create('/api/v1/posts', 'POST', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer valid-token',
        ]);

        $response = $middleware->handle($request, fn () => response()->json(['ok' => true], Response::HTTP_OK));

        $this->assertSame(200, $response->getStatusCode());
    }
}
