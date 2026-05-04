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

namespace App\Http\Controllers\Api\Support;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiTokenService
{
    /**
     * Issue token (DB-backed CMS auth)
     */
    public function issue(
        User $user,
        string $type = 'user',
        array $scopes = [],
        string $name = 'api'
    ): string {
        $plainToken = Str::random(64);
        $tokenHash = hash('sha256', $plainToken);

        DB::table('api_tokens')->insert([
            'user_id'      => $user->id,
            'name'         => $name,
            'type'         => $type,
            'token_hash'   => $tokenHash,
            'scopes'       => json_encode($scopes),
            'ip'           => request()->ip(),
            'user_agent'   => substr((string) request()->userAgent(), 0, 255),
            'last_used_at' => now(),
            'expires_at'   => Carbon::now()->addDays(7),
            'is_revoked'   => false,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return $plainToken;
    }

    /**
     * Resolve token → user + scopes
     */
    public function resolveUser(string $plainToken): ?User
    {
        $tokenHash = hash('sha256', $plainToken);

        $record = DB::table('api_tokens')
            ->where('token_hash', $tokenHash)
            ->where('is_revoked', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$record) {
            return null;
        }

        DB::table('api_tokens')
            ->where('id', $record->id)
            ->update(['last_used_at' => now()]);

        return User::find($record->user_id);
    }

    /**
     * Get scopes for token
     */
    public function getScopes(string $plainToken): array
    {
        $tokenHash = hash('sha256', $plainToken);

        $record = DB::table('api_tokens')
            ->where('token_hash', $tokenHash)
            ->first();

        if (!$record || empty($record->scopes)) {
            return [];
        }

        return json_decode($record->scopes, true) ?? [];
    }

    /**
     * Revoke token
     */
    public function revoke(string $plainToken): void
    {
        $tokenHash = hash('sha256', $plainToken);

        DB::table('api_tokens')
            ->where('token_hash', $tokenHash)
            ->update([
                'is_revoked' => true,
                'updated_at' => now(),
            ]);
    }
}