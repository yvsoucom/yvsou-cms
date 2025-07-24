<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
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



namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RightsService;
use Illuminate\Http\Request;
use App\Services\DomainService;
use App\Models\DomainManager;
use App\Models\DomainName;
use App\Models\DomainTree;
use Illuminate\Support\Facades\Auth;
class DomainViewController extends Controller
{
    // Public: accessible without login

    public function showSubDomains(string $groupid)
    {
        $children = (new DomainService())->get_children_by_groupid($groupid); // adapt this to a Laravel service or model
        $crumbs = [];
        foreach ($children as $child) {
            $childGroupId = $groupid . '.' . $child;
            $subPostViewUrl = route('post.postview', [
                'groupid' => $childGroupId,
            ]);
            $domainViewUrl = route('domainview.index', [
                'groupid' => $childGroupId,
            ]);
            $title = (new DomainService())->get_jointitle_by_id($child);
            $owner = (new User())->getAliasNameByID((new RightsService())->getManageDomainOwner($childGroupId));
            // $subview = '<span class="ms-4"><a href="' . $subPostViewUrl . '">View SubGroup Posts</a></span>';
            if ((new RightsService())->checkRightPermission($childGroupId, 'SHOWDIR')) {
                $scrumb = new \stdClass();
                $scrumb = [
                    'subPostViewUrl' => $subPostViewUrl,
                    'domainViewUrl' => $domainViewUrl,
                    'title' => $title,
                    'owner' => $owner,
                ];
                $crumbs[] = $scrumb;
            }
        }
        return $crumbs;
    }

    public function index($groupid)
    {
        if ($groupid == 0) {
            $groupid = DomainManager::getFirstGroupid();
            if (!$groupid) {
                if (!auth()->check()) {
                    return redirect()->route('login')->with('message', 'Please log in as Admin to create   top domain first.');
                }
                $user = auth()->user();
                if (!in_array($user->role, ['admin'])) {
                    return redirect()->route('home')->with('message', ' Only Administrator can create top domain !');
                }
                return redirect()->route('domainview.createsub', ['groupid' => 0]);
            }
            $groupid = (new DomainService())->get_topid_from_groupid($groupid);
        }
        $domainlinks = (new DomainService())->get_joinGroupLink_by_uniqid($groupid);
        $subdomain = $this->showSubDomains($groupid);
        $joincounts = [
            'joinnumbers' => DomainName::countJoinGroup($groupid),
            'pendingUsers' => DomainName::countRequestedGroup($groupid),
            'blockedUsers' => DomainName::countBlockGroup($groupid),

        ];
        $createpost = new \stdClass();
        $createUrl = route('post.create', [
            'groupid' => $groupid,
        ]);

        $createpost->url = $createUrl;
        $viewdomainposts = new \stdClass();
        $viewdomainpostUrl = route('post.postview', [
            'groupid' => $groupid,
        ]);

        $viewdomainposts->url = $viewdomainpostUrl;
        $id = (new DomainService())->get_id_from_groupid($groupid);
        $domaintitle = (new DomainService())->get_jointitle_by_id($id);
        $domaindescription = (new DomainService())->get_jointitledescription_by_id($id);
        return view('domainview.index', compact('groupid', 'domaintitle', 'domaindescription', 'domainlinks', 'subdomain', 'joincounts', 'createpost', 'viewdomainposts'));
    }

    public function createsub($groupid)
    {

        return view('domainview.createsub', compact('groupid'));
    }

    public function storesub(Request $request)
    {

        // Optional: validate the input
        $validated = $request->validate([
            'groupid' => 'required',
            'titles' => 'required|array',
            'descriptions' => 'required|array',
        ]);

        $user = auth()->user();

        // Optionally check if user is allowed

        if (!$user || !$user->canDomainRights($request->groupid, 'WRITE')) {

            return abort(403, 'Unauthorized: only admin or editor can create domains.');
        }


        // Store each language version

        $groupid = (new DomainService())->insertDomainTree($request->groupid, $request->titles, $request->descriptions);
        return redirect()->route('domainview.index', compact(['groupid']));

    }

    public function editdomain($groupid)
    {
        $domainproperty = DomainTree::getProperties($groupid);
        return view('domainview.editsub', compact(['groupid', 'domainproperty']));
    }

    public function editsub($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }

    }



    public function updatedomain(Request $request)
    {

        // Optional: validate the input
        $validated = $request->validate([
            'groupid' => 'required',
            'id' => 'required',
            'lang' => 'required',
            'title' => 'required|array',
            'description' => 'required|array',
        ]);

        $user = auth()->user();

        // Optionally check if user is allowed

        if (!$user || !$user->canDomainRights($request->groupid, 'WRITE')) {

            return abort(403, 'Unauthorized: only admin or editor can create domains.');
        }


        // Store each language version

        $groupid = (new DomainService())->updateDomainTree($request->title, $request->description, $request->id, $request->lang);
        return redirect()->route('domainview.index', compact(['groupid']));

    }



    public function trash($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }


    public function untrash($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }


    public function destroy($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }



    public function auditcheck($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }


    public function audituncheck($groupid)
    {
        if (!config('app.pro')) {
            return redirect('/upgrade')->with('error', 'Your company does not have Pro access.');
        }
    }


    public function rightsshow($groupid)
    {
        $user = Auth::user();

        if (!$user->canUpdateDomainRights($groupid)) {
            abort(403, 'Unauthorized');
        }


        $manager = DomainManager::where('domainid', $groupid)
            ->where('m_type', 'c')
            ->first();

        return view('domainview.rights', [
            'groupid' => $groupid,
            'rights' => [
                [
                    'key' => 'owner_rights',
                    'label' => 'Owner',
                    'value' => $manager->owner_rights,
                ],
                [
                    'key' => 'any_user_rights',
                    'label' => 'Any User',
                    'value' => $manager->any_user_rights,
                ],
                [
                    'key' => 'own_group_rights',
                    'label' => 'Own Group',
                    'value' => $manager->own_group_rights,
                ],
                [
                    'key' => 'grant_group_rights',
                    'label' => 'Grant Group',
                    'value' => $manager->grant_group_rights,
                ],
                [
                    'key' => 'grant_user_rights',
                    'label' => 'Grant User',
                    'value' => $manager->grant_user_rights,
                ],
            ]
        ]);

    }

    public function rightsupdate(Request $request, $groupid)
    {
        $user = Auth::user();

        if (!$user->canUpdateDomainRights($groupid)) {
            abort(403, 'Unauthorized');
        }
        $roleField = $request->input('role_key'); // ownerrights, anyuserrights, owngrouprights, etc.
        $endnum = 0;

        if ($request->write ==1)
            $endnum += 8;
        if ($request->read == 1)
            $endnum += 4;
        if ($request->addchild == 1)
            $endnum += 2;
        if ($request->showdir == 1)
            $endnum += 1;

        $rights =  $endnum ;

        DomainManager::whereRaw('TRIM(domainid) = ?', [$groupid])
            ->where('m_type', 'c')
            ->update([$roleField => $rights]);

        return redirect()->route('domainview.rights.show', ['groupid' => $groupid])
            ->with('success', 'Rights updated!');
    }
}




