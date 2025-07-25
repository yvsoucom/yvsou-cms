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

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// app/Http/Controllers/Admin/MailSettingsController.php
use App\Models\MailSetting;

class MailSettingsController extends Controller
{
    public function edit()
    {
        $settings = MailSetting::getSettings();
        logger("MailSettingsController");
        return view('admin.mail.mail-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        MailSetting::updateSettings($request->only([
            'host',
            'port',
            'encryption',
            'username',
            'password',
            'from_address',
            'from_name',
        ]));
        $settings = MailSetting::getSettings();
        config([
            'mail.mailers.smtp.host' => $settings['host'] ?? null,
            'mail.mailers.smtp.port' => $settings['port'] ?? null,
            'mail.mailers.smtp.encryption' => $settings['encryption'] ?? null,
            'mail.mailers.smtp.username' => $settings['username'] ?? null,
            'mail.mailers.smtp.password' => $settings['password'] ?? null,
            'mail.from.address' => $settings['from_address'] ?? null,
            'mail.from.name' => $settings['from_name'] ?? null,
        ]);
        return redirect()->back()->with('success', 'Mail settings updated.');
    }
}
