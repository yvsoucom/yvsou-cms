<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
// SPDX-FileContributor: Lican Huang
//
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
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
