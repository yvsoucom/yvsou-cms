<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.,     All rights reserved.
 * Author: Lican Huang
 * 
 * SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
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

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Load default locale from APP_LOCALE in .env or fallback to 'en'
        $defaultLocale = env('APP_LOCALE', 'en');
        $supportedLocales = config('yvsou_config.LANGUAGESET', ['en', 'zh', 'ja', 'fr']);

        // Priority: session -> cookie -> .env (APP_LOCALE)
        $locale = session('locale')
            ?? ($_COOKIE['locale'] ?? null)
            ?? $defaultLocale;

        if (!in_array($locale, $supportedLocales)) {
            $locale = $defaultLocale;
        }

        app()->setLocale($locale);

        // Debug logging
        logger('SetLocale middleware applied', [
            'locale_set' => $locale,
            'session_locale' => session('locale'),
            'cookie_locale' => $_COOKIE['locale'] ?? null,
            'env_locale' => $defaultLocale,
        ]);

        return $next($request);
    }
}
