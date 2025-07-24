<?php
/**
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-29
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

namespace App\Http\Controllers\Admin;

use App\Services\LocaleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CustomConfigSettingsController extends Controller
{
    public function edit()
    {
        $locale = (new LocaleService())->getcurlangcode();  // Or pass in a locale param

        $file = resource_path("lang/{$locale}/pages.php");
        $pages = file_exists($file) ? include $file : [];

        return view('admin.customconfig.edit', [
            'locale' => $locale,
            'pages' => $pages,
        ]);
    }

    public function update(Request $request)
    {
        $locale = (new LocaleService())->getcurlangcode();  // Use the same locale you edited

        // Validate only this locale’s fields
        $validated = $request->validate([
            "about" => 'required|string',
            "contact" => 'required|string',
            "terms" => 'required|string',
            "privacy" => 'required|string',
        ]);

        // Save to file
        $php = "<?php\n\nreturn " . var_export($validated, true) . ";\n";
        file_put_contents(resource_path("lang/{$locale}/pages.php"), $php);

        return redirect()->back()->with('success', "Updated {$locale} content!");
    }
}
