<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\Api\Requests\StoreCommentRequest;
use App\Http\Controllers\Api\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class CommentApiTest extends TestCase
{

    private CommentController $controller;
    protected function setUp(): void
    {
        parent::setUp();
        require base_path('routes/api.php');

        $this->controller = app(CommentController::class);
    }

    public function test_controller_is_instantiated(): void
    {
        $this->assertInstanceOf(CommentController::class, $this->controller);
    }

    public function test_store_comment_validation_error_case(): void
    {
        $validator = Validator::make([], (new StoreCommentRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('postid', $validator->errors()->toArray());
        $this->assertArrayHasKey('comment_content', $validator->errors()->toArray());
    }

    public function test_store_comment_validation_success_case(): void
    {
        $validator = Validator::make([
            'postid' => 1,
            'comment_content' => 'Hello API comment',
        ], (new StoreCommentRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_update_comment_validation_success_case(): void
    {
        $validator = Validator::make([
            'comment_content' => 'Updated comment',
        ], (new UpdateCommentRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_public_routes_are_accessible_without_auth_middleware(): void
    {
        $indexRoute = $this->resolveRoute('GET', '/api/v1/comments');
        $showRoute = $this->resolveRoute('GET', '/api/v1/comments/1');

        $this->assertNotContains(ApiTokenMiddleware::class, $indexRoute->gatherMiddleware());
        $this->assertNotContains(ApiTokenMiddleware::class, $showRoute->gatherMiddleware());
    }

    public function test_write_routes_require_auth_middleware(): void
    {
        $storeRoute = $this->resolveRoute('POST', '/api/v1/comments');
        $updateRoute = $this->resolveRoute('PATCH', '/api/v1/comments/1');
        $destroyRoute = $this->resolveRoute('DELETE', '/api/v1/comments/1');

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
