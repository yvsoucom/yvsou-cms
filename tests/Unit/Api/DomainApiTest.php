<?php

declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Http\Controllers\Api\DomainController;
use App\Http\Controllers\Api\Middleware\ApiTokenMiddleware;
use App\Http\Controllers\Api\Requests\StoreDomainRequest;
use App\Http\Controllers\Api\Requests\UpdateDomainRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class DomainApiTest extends TestCase
{

    private DomainController $controller;
    protected function setUp(): void
    {
        parent::setUp();
        require base_path('routes/api.php');

        $this->controller = app(DomainController::class);
    }

    public function test_controller_is_instantiated(): void
    {
        $this->assertInstanceOf(DomainController::class, $this->controller);
    }

    public function test_store_domain_validation_error_case(): void
    {
        $validator = Validator::make([], (new StoreDomainRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('domainid', $validator->errors()->toArray());
        $this->assertArrayHasKey('userid', $validator->errors()->toArray());
    }

    public function test_store_domain_validation_success_case(): void
    {
        $validator = Validator::make([
            'domainid' => 'group-1',
            'userid' => 1,
            'm_type' => 'c',
        ], (new StoreDomainRequest())->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_update_domain_validation_success_case(): void
    {
        $validator = Validator::make(['bHide' => true], (new UpdateDomainRequest())->rules());
        $this->assertFalse($validator->fails());
    }

    public function test_public_routes_are_accessible_without_auth_middleware(): void
    {
        $indexRoute = $this->resolveRoute('GET', '/api/v1/domains');
        $showRoute = $this->resolveRoute('GET', '/api/v1/domains/group-1');

        $this->assertNotContains(ApiTokenMiddleware::class, $indexRoute->gatherMiddleware());
        $this->assertNotContains(ApiTokenMiddleware::class, $showRoute->gatherMiddleware());
    }

    public function test_write_routes_require_auth_middleware(): void
    {
        $storeRoute = $this->resolveRoute('POST', '/api/v1/domains');
        $updateRoute = $this->resolveRoute('PUT', '/api/v1/domains/group-1');
        $destroyRoute = $this->resolveRoute('DELETE', '/api/v1/domains/group-1');

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
