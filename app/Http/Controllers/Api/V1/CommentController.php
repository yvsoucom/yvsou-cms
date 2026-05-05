<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 * SPDX-FileContributor: Lican Huang
 * @created 2026-05-05
 *
 * SPDX-License-Identifier: GPL-3.0-or-later
 * License: Dual Licensed – GPLv3 or Commercial
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * As an alternative to GPLv3, commercial licensing is available for organizations
 * or individuals requiring proprietary usage, private modifications, or support.
 *
 * Contact: yvsoucom@gmail.com
 * GPL License: https://www.gnu.org/licenses/gpl-3.0.html
 */


declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Support\Api\ApiResponse;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Controllers\Controller;
use App\Models\DomainComment;
use Illuminate\Http\JsonResponse;
use Throwable;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $comments = DomainComment::query()->paginate(20);
            return ApiResponse::success($comments, 'Comments fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch comments', ['exception' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $comment = DomainComment::query()->find($id);
            if ($comment === null) {
                return ApiResponse::error('Comment not found', ['id' => $id], 404);
            }

            return ApiResponse::success($comment, 'Comment fetched');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch comment', ['exception' => $e->getMessage()], 500);
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
            return ApiResponse::success($comment, 'Comment created', 201);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to create comment', ['exception' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateCommentRequest $request, int $id): JsonResponse
    {
        try {
            $comment = DomainComment::query()->find($id);
            if ($comment === null) {
                return ApiResponse::error('Comment not found', ['id' => $id], 404);
            }

            $comment->fill($request->validated());
            $comment->save();

            return ApiResponse::success($comment->fresh(), 'Comment updated');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to update comment', ['exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $comment = DomainComment::query()->find($id);
            if ($comment === null) {
                return ApiResponse::error('Comment not found', ['id' => $id], 404);
            }

            $comment->delete();
            return ApiResponse::success(['id' => $id], 'Comment deleted');
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to delete comment', ['exception' => $e->getMessage()], 500);
        }
    }
}
