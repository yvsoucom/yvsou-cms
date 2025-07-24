<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
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


namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;


class SetLocale
{

    public function handle($request, Closure $next)
    {
        $defaultLocale = config('yvsou_config.DEFAULT_LANGUAGE', 'en');
        $supportedLocales = config('yvsou_config.LANGUAGESET', ['en', 'zh', 'ja', 'fr']);

        $locale = session('locale')
            ?? ($_COOKIE['locale'] ?? null)
            ?? $defaultLocale;

        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale($defaultLocale);
        }

        // Optional: Log for debugging
        logger('SetLocale middleware applied', [
            'locale_set' => app()->getLocale(),
            'session_locale' => session('locale'),
            'cookie_locale' => $_COOKIE['locale'] ?? null,
            'default_locale' => $defaultLocale,
        ]);
        return $next($request);
    }
    public function handle1($request, Closure $next)
    {
        logger('Locale in SetLocale middleware before', [app()->getLocale()]);

        // $locale = Cookie::get('locale', config('yvsou_config.DEFAULT_LANGUAGE'));
        $locale = $_COOKIE['locale'] ?? 'en';
        logger('cookieLocale in SetLocale middleware  ', [$locale]);

        if (in_array($locale, config('yvsou_config.LANGUAGESET'))) {
            app()->setLocale($locale);
            logger('cookieLocale in SetLocale middleware after', [app()->getLocale()]);

        }

        return $next($request);
    }
}





