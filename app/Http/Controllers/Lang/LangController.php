<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.    All rights reserved.
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

namespace App\Http\Controllers\Lang;
use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use Illuminate\Http\Request;
use App\Services\PagelineService;
use App\Services\PostService;
use App\Services\RightsService;
use App\Services\UserService;
use App\Services\DomainService;
use App\Models\DomainPostId;
use App\Models\DomainManager;
use App\Models\User;
use App\Models\DomainPost;
use Illuminate\Support\Facades\Cookie;
use App\Services\ConstantService;

class LangController extends Controller
{
    public function setLang($locale)
    {
        $availableLocales = config('yvsou_config.LANGUAGESET');
        logger('availableLocales', $availableLocales); // Temporarily check this
        logger('localebefore', [$locale]); // Temporarily check this
        if (in_array($locale, $availableLocales)) {

            app()->setLocale($locale);
            logger('set locale cookie after', [app()->getLocale()]); // Temporarily check this 
             
            setcookie('locale', $locale, time() + (60 * 60 * 24 * 30), '/'); // expires in 30 days, available site-wide

           // return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 30)); // valid locale
        }

        return redirect()->back(); // don't set cookie for invalid locale
    }
}