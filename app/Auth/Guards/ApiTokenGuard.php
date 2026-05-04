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

namespace App\Auth\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use App\Http\Controllers\Api\Support\ApiTokenService;

class ApiTokenGuard implements Guard
{
    protected ?Authenticatable $user = null;

    public function __construct(
        protected ApiTokenService $tokens
    ) {
    }

    public function user(): ?Authenticatable
    {
        if ($this->user) {
            return $this->user;
        }

        $token = request()->bearerToken();

        if (!$token) {
            return null;
        }

        return $this->user = $this->tokens->resolveUser($token);
    }

    public function id(): ?int
    {
        return $this->user()?->getAuthIdentifier();
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * ✅ REQUIRED in Laravel 12 Guard contract
     */
    public function hasUser(): bool
    {
        return $this->user !== null;
    }

    public function setUser(?Authenticatable $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function validate(array $credentials = []): bool
    {
        return false; // not used in token guards
    }
}