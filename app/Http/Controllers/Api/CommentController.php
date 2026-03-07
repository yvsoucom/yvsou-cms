<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ApiResponse;
use App\Http\Controllers\Api\Requests\StoreCommentRequest;
use App\Http\Controllers\Api\Requests\UpdateCommentRequest;
use App\Http\Controllers\Controller;
use App\Models\DomainComment;
use Illuminate\Http\JsonResponse;
use Throwable;

class CommentController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $comments = DomainComment::query()->paginate(20);
            return $this->success($comments, 'Comments fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch comments', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $comment = DomainComment::query()->find($id);
            if ($comment === null) {
                return $this->error('Comment not found', ['id' => $id], 404);
            }

            return $this->success($comment, 'Comment fetched');
        } catch (Throwable $e) {
            return $this->error('Failed to fetch comment', ['exception' => $e->getMessage()], 500);
        }
    }

    public function store(StoreCommentRequest $request): JsonResponse
    {
        try {
            $payload = $request->validated();
            $payload['userid'] = $payload['userid'] ?? (auth()->id() ?? 0);
            $payload['comment_date'] = now();
            $payload['comment_ip'] = request()->ip() ?? '0.0.0.0';

            $comment = DomainComment::query()->create($payload);
            return $this->success($comment, 'Comment created', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create comment', ['exception' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateCommentRequest $request, int $id): JsonResponse
    {
        try {
            $comment = DomainComment::query()->find($id);
            if ($comment === null) {
                return $this->error('Comment not found', ['id' => $id], 404);
            }

            $comment->fill($request->validated());
            $comment->save();

            return $this->success($comment->fresh(), 'Comment updated');
        } catch (Throwable $e) {
            return $this->error('Failed to update comment', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $comment = DomainComment::query()->find($id);
            if ($comment === null) {
                return $this->error('Comment not found', ['id' => $id], 404);
            }

            $comment->delete();
            return $this->success(['id' => $id], 'Comment deleted');
        } catch (Throwable $e) {
            return $this->error('Failed to delete comment', ['exception' => $e->getMessage()], 500);
        }
    }
}
