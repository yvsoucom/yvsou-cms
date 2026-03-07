<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\Requests\StorePostRequest;
use App\Http\Controllers\Api\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class PostApiTest extends TestCase
{

    private PostController $controller;
    protected function setUp(): void
    {
        parent::setUp();
        require base_path('routes/api.php');

        $this->controller = app(PostController::class);
    }

    public function test_controller_is_instantiated(): void
    {
        $this->assertInstanceOf(PostController::class, $this->controller);
    }

    public function test_store_post_validation_error_case(): void
    {
        $rules = (new StorePostRequest())->rules();
        $validator = Validator::make([], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('post_title', $validator->errors()->toArray());
        $this->assertArrayHasKey('post_content', $validator->errors()->toArray());
    }

    public function test_store_post_validation_success_case(): void
    {
        $rules = (new StorePostRequest())->rules();
        $validator = Validator::make([
            'post_title' => 'API Post',
            'post_content' => 'Post body',
            'post_status' => 1,
        ], $rules);

        $this->assertFalse($validator->fails());
    }

    public function test_update_post_validation_success_case(): void
    {
        $rules = (new UpdatePostRequest())->rules();
        $validator = Validator::make(['post_title' => 'Updated'], $rules);

        $this->assertFalse($validator->fails());
    }

    public function test_public_routes_are_accessible_without_auth_middleware(): void
    {
        $indexRoute = $this->resolveRoute('GET', '/api/v1/posts');
        $showRoute = $this->resolveRoute('GET', '/api/v1/posts/1');

        $this->assertNotContains(ApiTokenMiddleware::class, $indexRoute->gatherMiddleware());
        $this->assertNotContains(ApiTokenMiddleware::class, $showRoute->gatherMiddleware());
    }

    public function test_write_routes_require_auth_middleware(): void
    {
        $storeRoute = $this->resolveRoute('POST', '/api/v1/posts');
        $updateRoute = $this->resolveRoute('PUT', '/api/v1/posts/1');
        $destroyRoute = $this->resolveRoute('DELETE', '/api/v1/posts/1');

        $this->assertContains(ApiTokenMiddleware::class, $storeRoute->gatherMiddleware());
        $this->assertContains(ApiTokenMiddleware::class, $updateRoute->gatherMiddleware());
        $this->assertContains(ApiTokenMiddleware::class, $destroyRoute->gatherMiddleware());
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
