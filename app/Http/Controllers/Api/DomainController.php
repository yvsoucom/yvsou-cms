<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ApiResponse;
use App\Http\Controllers\Api\Requests\StoreDomainRequest;
use App\Http\Controllers\Api\Requests\UpdateDomainRequest;
use App\Http\Controllers\Controller;
use App\Models\DomainManager;
use Illuminate\Http\JsonResponse;
use Throwable;

class DomainController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $domains = DomainManager::query()->paginate(20);
            return $this->success($domains, 'Domains fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch domains', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $domain = DomainManager::query()->find($id);
            if ($domain === null) {
                return $this->error('Domain not found', ['id' => $id], 404);
            }

            return $this->success($domain, 'Domain fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch domain', ['exception' => $e->getMessage()], 500);
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
            return $this->success($domain, 'Domain created', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create domain', ['exception' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateDomainRequest $request, string $id): JsonResponse
    {
        try {
            $domain = DomainManager::query()->find($id);
            if ($domain === null) {
                return $this->error('Domain not found', ['id' => $id], 404);
            }

            $domain->fill($request->validated());
            $domain->save();

            return $this->success($domain->fresh(), 'Domain updated');
        } catch (Throwable $e) {
            return $this->error('Failed to update domain', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $domain = DomainManager::query()->find($id);
            if ($domain === null) {
                return $this->error('Domain not found', ['id' => $id], 404);
            }

            $domain->delete();
            return $this->success(['id' => $id], 'Domain deleted');
        } catch (Throwable $e) {
            return $this->error('Failed to delete domain', ['exception' => $e->getMessage()], 500);
        }
    }
}
