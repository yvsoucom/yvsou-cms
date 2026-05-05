<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Support\Api\ApiResponse;
use App\Http\Requests\Api\V1\StoreDomainRequest;
use App\Http\Requests\Api\V1\UpdateDomainRequest;
use App\Http\Controllers\Controller;
use App\Models\DomainManager;
use Illuminate\Http\JsonResponse;
use Throwable;

class DomainController extends Controller
{
    
    public function index(): JsonResponse
    {
        try {
            $domains = DomainManager::query()->paginate(20);
            return ApiResponse::success($domains, 'Domains fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch domains', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $domain = DomainManager::query()->find($id);
            if ($domain === null) {
                return ApiResponse::error('Domain not found', ['id' => $id], 404);
            }

            return ApiResponse::success($domain, 'Domain fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch domain', ['exception' => $e->getMessage()], 500);
        }
    }

    public function store(StoreDomainRequest $request): JsonResponse
    {
        try {
            $payload = $request->validated();
            $payload['IP'] = request()->ip() ?? '0.0.0.0';
            $payload['cDate'] = now();
            $payload['m_type'] = $payload['m_type'] ?? 'c';

            $domain = DomainManager::query()->create($payload);
            return ApiResponse::success($domain, 'Domain created', 201);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to create domain', ['exception' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateDomainRequest $request, string $id): JsonResponse
    {
        try {
            $domain = DomainManager::query()->find($id);
            if ($domain === null) {
                return ApiResponse::error('Domain not found', ['id' => $id], 404);
            }

            $domain->fill($request->validated());
            $domain->save();

            return ApiResponse::success($domain->fresh(), 'Domain updated');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to update domain', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $domain = DomainManager::query()->find($id);
            if ($domain === null) {
                return ApiResponse::error('Domain not found', ['id' => $id], 404);
            }

            $domain->delete();
            return ApiResponse::success(['id' => $id], 'Domain deleted');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to delete domain', ['exception' => $e->getMessage()], 500);
        }
    }
}
