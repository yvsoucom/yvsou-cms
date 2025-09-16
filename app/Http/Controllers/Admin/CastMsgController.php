<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
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
use App\Models\DomainMsgCast;
use Illuminate\Http\Request;
use App\Services\LocaleService;

class CastMsgController extends Controller
{

    public function edit()
    {
        return view('admin.castmsg.edit');
    }
    public function update(Request $request)
    {

        $validated = $request->validate([

            'message' => 'required',

        ]);

        $msg = $validated['message'];
        $lang = (new LocaleService())->getcurlang();


        DomainMsgCast::create([
            'msg_content' => $msg,
            'lang' => $lang,
            'cast_type' => 0,
            'dtime' => now(),
        ]);

        return redirect()->route('home')
            ->with('message', 'Broadcast message sent!');

    }
}