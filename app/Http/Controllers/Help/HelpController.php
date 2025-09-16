<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.    All rights reserved.
 * Author: Lican Huang
 * @created 2025-06-28
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
namespace App\Http\Controllers\Help;

use App\Services\LocaleService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class HelpController extends Controller
{

    public function about()
    {
        $lang = (new LocaleService())->getcurlangcode();

        // Build the path to your .md file
        $path = resource_path("docs/help/{$lang}/about.md");

        // Check if file exists
        if (file_exists($path)) {
            $aboutMd = file_get_contents($path);
        } else {
            $aboutMd = '# Content not found';
        }
        $aboutMdHtml = Str::markdown($aboutMd);
        return view('help.about', compact('aboutMdHtml'));
    }

    public function menu()
    {
        $lang = (new LocaleService())->getcurlangcode();

        // Build the path to your .md file
        $path = resource_path("docs/help/{$lang}/menu.md");

        // Check if file exists
        if (file_exists($path)) {
            $menuMd = file_get_contents($path);
        } else {
            $menuMd = '# Content not found';
        }
        $menuHtml = Str::markdown($menuMd);
        return view('help.menu', compact('menuHtml'));

    }

    public function search()
    {


    }


}