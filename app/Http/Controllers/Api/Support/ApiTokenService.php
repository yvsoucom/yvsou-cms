<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ApiTokenService
{
    private const TOKEN_TTL_SECONDS = 60 * 60 * 24 * 7;

    public function issue(User $user): string
    {
        $plain = Str::random(64);
        $key = $this->cacheKey($plain);

        Cache::put($key, [
            'user_id' => $user->id,
            'issued_at' => now()->toIso8601String(),
        ], self::TOKEN_TTL_SECONDS);

        return $plain;
    }

    public function resolveUser(string $plainToken): ?User
    {
        $payload = Cache::get($this->cacheKey($plainToken));
        if (!is_array($payload) || !isset($payload['user_id'])) {
            return null;
        }

        return User::find($payload['user_id']);
    }

    public function revoke(string $plainToken): void
    {
        Cache::forget($this->cacheKey($plainToken));
    }

    private function cacheKey(string $plainToken): string
    {
        return 'api:v1:token:' . hash('sha256', $plainToken);
    }
}
