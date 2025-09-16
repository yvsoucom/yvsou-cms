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
namespace App\Http\Controllers\Msg;

use App\Services\LocaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function showMessages(Request $request)
    {

        $lang = (new LocaleService)->getCurLang();
        $castMessages = collect([]);
        $userMessages = collect([]);
        $groupcastMessages = collect([]);


        $castMessages = DB::table('domain_msg_casts')
            ->where('msg_handled', 0)
            ->where('lang', $lang)
            ->orderByDesc('dtime')
            ->get();


        $lastReadTime = null;

        if (Auth::check()) {
            $user = Auth::user();


            $groupcastMessages = DB::table('domain_msg_centers as m')
                ->join('users as u', 'm.from_userid', '=', 'u.id')
                ->join('domain_names as dn', function ($join) use ($user) {
                    $join->on('m.to_domainid', '=', 'dn.domainid')
                        ->where('dn.userid', '=', $user->id)
                        ->where('dn.checked', '=', 0);
                })
                ->where('m.msg_handled', 0)
                ->where('m.cast_type', 1)
                ->where('m.lang', $lang)
                ->orderByDesc('m.dtime')
                ->select('m.to_domainid', 'm.msg_content', 'm.dtime', 'u.name as from_username')
                ->get();

            $userMessages = DB::table('domain_msg_centers as m')
                ->join('users as u', 'm.from_userid', '=', 'u.id')
                ->where('m.to_userid', $user->id)
                ->where('m.msg_handled', 0)
                ->where('m.lang', $lang)
                ->orderByDesc('m.dtime')
                ->select('m.msg_content', 'm.dtime', 'u.name as from_username')
                ->get();

            $lastReadTime = DB::table('domain_msg_reads')
                ->where('userid', $user->id)
                ->where('lang', $lang)
                ->value('readtime');

            // Insert or update read time
            DB::table('domain_msg_reads')->updateOrInsert(
                ['userid' => $user->id, 'lang' => $lang],
                ['readtime' => now()]
            );
        }

        $allMessages = array_merge(
            $castMessages->all(),
            $groupcastMessages->all(),
            $userMessages->all()
        );
        return view('message.message', compact(
            'allMessages',
            'lastReadTime'
        ));
    }

}
