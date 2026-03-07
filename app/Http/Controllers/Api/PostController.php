<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ApiResponse;
use App\Http\Controllers\Api\Requests\StorePostRequest;
use App\Http\Controllers\Api\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use App\Models\DomainPost;
use Illuminate\Http\JsonResponse;
use Throwable;

class PostController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $posts = DomainPost::query()->paginate(20);
            return $this->success($posts, 'Posts fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch posts', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $post = DomainPost::query()->find($id);
            if ($post === null) {
                return $this->error('Post not found', ['id' => $id], 404);
            }

            return $this->success($post, 'Post fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch post', ['exception' => $e->getMessage()], 500);
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

            return $this->success($post, 'Post created', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create post', ['exception' => $e->getMessage()], 500);
        }
    }

    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        try {
            $post = DomainPost::query()->find($id);
            if ($post === null) {
                return $this->error('Post not found', ['id' => $id], 404);
            }

            $payload = $request->validated();
            $payload['updated_at'] = now();
            $post->fill($payload);
            $post->save();

            return $this->success($post->fresh(), 'Post updated');
        } catch (Throwable $e) {
            return $this->error('Failed to update post', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $post = DomainPost::query()->find($id);
            if ($post === null) {
                return $this->error('Post not found', ['id' => $id], 404);
            }

            $post->delete();
            return $this->success(['id' => $id], 'Post deleted');
        } catch (Throwable $e) {
            return $this->error('Failed to delete post', ['exception' => $e->getMessage()], 500);
        }
    }
}
