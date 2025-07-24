<?php
/**
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-07-24
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
