<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Support\Api\ApiResponse;

use App\Http\Requests\Api\V1\StorePostRequest;
use App\Http\Requests\Api\V1\UpdatePostRequest;
use App\Http\Controllers\Controller;
use App\Models\DomainPost;
use Illuminate\Http\JsonResponse;
use Throwable;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $posts = DomainPost::query()->paginate(20);
            return ApiResponse::success($posts, 'Posts fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch posts', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $post = DomainPost::query()->find($id);
            if ($post === null) {
                return ApiResponse::error('Post not found', ['id' => $id], 404);
            }

            return ApiResponse::success($post, 'Post fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch post', ['exception' => $e->getMessage()], 500);
        }
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $payload = $request->validated();
            $payload['post_author'] = $payload['post_author'] ?? (auth()->id() ?? 0);
            $payload['revised_author'] = $payload['revised_author'] ?? (auth()->id() ?? 0);
            $payload['post_date'] = now();
            $payload['updated_at'] = now();

            $post = DomainPost::query()->create($payload);

            return ApiResponse::success($post, 'Post created', 201);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to create post', ['exception' => $e->getMessage()], 500);
        }
    }

    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        try {
            $post = DomainPost::query()->find($id);
            if ($post === null) {
                return ApiResponse::error('Post not found', ['id' => $id], 404);
            }

            $payload = $request->validated();
            $payload['updated_at'] = now();
            $post->fill($payload);
            $post->save();

            return ApiResponse::success($post->fresh(), 'Post updated');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to update post', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $post = DomainPost::query()->find($id);
            if ($post === null) {
                return ApiResponse::error('Post not found', ['id' => $id], 404);
            }

            $post->delete();
            return ApiResponse::success(['id' => $id], 'Post deleted');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to delete post', ['exception' => $e->getMessage()], 500);
        }
    }
}
