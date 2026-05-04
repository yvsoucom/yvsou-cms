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

// app/Services/ApiTokenService.php

namespace App\Services;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Support\Str;

class ApiTokenService
{
    public function issue(
        User $user,
        string $type = 'user',
        array $scopes = [],
        ?string $name = null,
        ?int $ttlDays = 7
    ): string {
        $plain = Str::random(64);

        ApiToken::create([
            'user_id' => $user->id,
            'name' => $name,
            'type' => $type,
            'scopes' => $scopes,
            'token_hash' => hash('sha256', $plain),
            'ip' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'expires_at' => $ttlDays ? now()->addDays($ttlDays) : null,
        ]);

        return $plain;
    }

    public function resolve(string $plainToken): ?ApiToken
    {
        $hash = hash('sha256', $plainToken);

        $token = ApiToken::where('token_hash', $hash)
            ->where('is_revoked', false)
            ->first();

        if (!$token) return null;

        if ($token->expires_at && $token->expires_at->isPast()) {
            $token->delete();
            return null;
        }

        $token->update([
            'last_used_at' => now(),
        ]);

        return $token;
    }

    public function user(string $plainToken): ?User
    {
        return $this->resolve($plainToken)?->user;
    }

    public function revoke(string $plainToken): void
    {
        ApiToken::where('token_hash', hash('sha256', $plainToken))
            ->update(['is_revoked' => true]);
    }

    public function revokeAll(User $user): void
    {
        ApiToken::where('user_id', $user->id)
            ->update(['is_revoked' => true]);
    }
}