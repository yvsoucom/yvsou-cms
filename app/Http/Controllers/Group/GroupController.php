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

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\DomainName;
use App\Services\LocaleService;
use Illuminate\Http\Request;
use App\Services\PagelineService;
use App\Services\PostService;
use App\Services\RightsService;
use App\Services\UserService;
use App\Services\DomainService;
use App\Models\DomainPostId;
use App\Models\DomainManager;
use App\Models\DomainMsgCenter;
use App\Models\User;
use App\Models\DomainPost;


class GroupController extends Controller
{


    public function joingroup($groupid)
    {
        DomainName::joinGroup($groupid);

        return back()->with('message', 'You have left the group.');
    }

    public function quitgroup($groupid)
    {
        DomainName::quitGroup($groupid);

        return back()->with('message', 'You have left the group.');
    }
    public function approvegroup(Request $request)
    {

        $validated = $request->validate([
            'groupid' => 'required',

        ]);
        $groupid = $request->groupid;
        $users = DomainName::getApplyUsers($request->groupid);
        return view('group.approve', compact('groupid', 'users'));

    }

    public function storeapprove(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required',
            'selected_users' => 'required',
        ]);
        $groupid = $request->groupid;
        $seleuserids = $request->selected_users;

        foreach ($seleuserids as $userid) {

            DomainName::approveGroup($groupid, $userid);

        }

        return redirect()->route('domainview.index', $groupid);
    }

    public function sendMessage2Users(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required',

        ]);
        $groupid = $request->groupid;
        $users = DomainName::getJoinUsers($groupid);
        return view('group.message', compact('groupid', 'users'));

    }

    public function groupmessage(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required',

        ]);
        $groupid = $request->groupid;
        $users = DomainName::getJoinUsers($groupid);
        return view('group.editcastmessage', compact('groupid', 'users'));

    }
    public function editmessage(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required',
            'selected_users' => 'required',
        ]);

        $groupid = $request->groupid;
        $userids = $request->selected_users;
        return view('group.editmessage', compact('groupid', 'userids'));

    }



    public function castmessagestore(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required',
            'message' => 'required',
        ]);

        $groupid = $validated['groupid'];
        $msg = $validated['message'];
        $lang = (new LocaleService())->getcurlang();
        // Create the message — adjust columns as needed
        DomainMsgCenter::create([
            'to_domainid' => $groupid,
            'msg_content' => $msg,
            'from_userid' => auth()->id(),
            'to_userid' => 0,
            'cast_type' => 1,
            'lang' => $lang,
            'dtime' => now(),
        ]);

        return redirect()->route('domainview.index', ['groupid' => $groupid])
            ->with('message', 'Broadcast message sent!');
    }


    public function messagestore(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required',
            'message' => 'required',
            'userids' => 'required',
        ]);

        $userids = $request->userids;

        $groupid = $validated['groupid'];
        $msg = $validated['message'];
        $lang = (new LocaleService())->getcurlang();

        foreach ($userids as $userid) {
            DomainMsgCenter::create([
                'to_domainid' => $groupid,
                'msg_content' => $msg,
                'from_userid' => auth()->id(),
                'to_userid' => $userid,
                'cast_type' => 0,
                'lang' => $lang,
                'dtime' => now(),
            ]);
        }

        return redirect()->route('domainview.index', ['groupid' => $groupid])
            ->with('message', 'Broadcast message sent!');


    }

    public function invitegroup($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }

    public function auditcheckgroup($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }

    public function unauditcheckgroup($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }

    public function setpublic(Request $request)
    {
        logger('setpublic');
        $request->validate([
            'groupid' => 'required|String' // or string if your IDs are UUIDs
        ]);

        $groupid = $request->groupid;
        logger($groupid);
        DomainManager::setPublic($groupid);
        return back()->with('message', 'You have setpublic.');
    }

    public function setprivate(Request $request)
    {
        logger('setprivate');
        $request->validate([
            'groupid' => 'required|String' // or string if your IDs are UUIDs
        ]);

        $groupid = $request->groupid;
        logger($groupid);
        DomainManager::setPrivate($groupid);

        return back()->with('message', 'You have setprivate.');
    }
}

