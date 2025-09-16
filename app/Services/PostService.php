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

namespace App\Services;
use App\Models\DomainPostId;
use App\Models\DomainManager;
use Illuminate\Log\Logger;
use Illuminate\Support\Carbon;
use App\Services\LocaleService;
use App\Models\DomainPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class PostService
{
    function getPostTitle($ID)
    {
        return DomainPost::where('id', $ID)->value('post_title');
    }

    function getPostAuthor($ID)
    {
        return DomainPost::where('id', $ID)->value('post_author');
    }

    function getPostDate($ID)
    {
        return DomainPost::where('id', $ID)->value('post_date');
    }

    function getPostRights($ID)
    {
        return DomainPost::where('id', $ID)->value('rights');
    }

    function getComment_rights($ID)
    {
        return DomainPost::where('id', $ID)->value('comment_rights');
    }

    function getPostFromPostid($pid)
    {
        $post = DomainPost::find($pid);

        return $post;

    }



    function getDomainPostGroups(
        $groupid,
        int $srec
    ) {
        $langid = (new LocaleService())->getcurlang();
        // $alist = ConstantService::$alist;
        //  $alist = json_decode(Cookie::get('alist'), true) ?? ConstantService::$alist;
        $slen = ConstantService::$slen;
        $postsQuery = DB::table('domain_post_ids')
            ->useIndex('groupblogdate')
            ->select('postid', 'lang', 'groupid', 'gDate', 'guserID', 'isTrash')
            ->where('lang', $langid)
            ->where('groupid', '=', $groupid);

        return $posts = $postsQuery
            ->orderByDesc('gDate')
            ->offset($srec)
            ->limit($slen)
            ->get();
    }

    function getAllSubPostGroups(
        $groupid,
        int $srec
    ) {
        $langid = (new LocaleService())->getcurlang();

        $slen = ConstantService::$slen;
        $postsQuery = DB::table('domain_post_ids')
            ->useIndex('groupblogdate')
            ->select('postid', 'lang', 'groupid', 'gDate', 'guserID', 'isTrash')
            ->where('lang', $langid)
            ->where('groupid', 'like', $groupid . '%')
            ->orWhere('groupid', 'like', $groupid . '.%');
        return $posts = $postsQuery
            ->orderByDesc('gDate')
            ->offset($srec)
            ->limit($slen)
            ->get();
    }
    function getPostCounts(
        $groupid,
        $alist
    ) {
        //    $alist = json_decode(Cookie::get('alist'), true) ?? ConstantService::$alist;

        if ($alist)
            $counts = $this->getAllSubPostCounts($groupid);
        else
            $counts = $this->getDomainPostCounts($groupid);

        return $counts;
    }


    function getDomainPostCounts(
        $groupid
    ) {
        $langid = (new LocaleService())->getcurlang();

        $countQuery = DB::table('domain_post_ids')
            ->useIndex('groupblogdate')
            ->where('lang', $langid)
            ->where('groupid', '=', trim($groupid));

        $total = $countQuery->distinct('postid')->count('postid');
        return $total;
    }
    function getAllSubPostCounts(
        $groupid
    ) {
        $langid = (new LocaleService())->getcurlang();

        $countQuery = DB::table('domain_post_ids')
            ->useIndex('groupblogdate')
            ->where('lang', $langid)
            ->where('groupid', 'like', trim($groupid) . '%')
            ->orWhere('groupid', 'like', trim($groupid) . '.%');

        $total = $countQuery->distinct('id')->count('id');
        return $total;
    }



    public function getPosts(
        $groupid,
        int $srec = 0,
    ): array {
        $slen = 150;
        $alist = json_decode(Cookie::get('alist'), true) ?? ConstantService::$alist;
        if ($alist)
            $results = $this->getAllSubPostGroups($groupid, $srec);
        else
            $results = $this->getDomainPostGroups($groupid, $srec);
        Logger(message: "postgroups" . $results);
        $posts = [];
        foreach ($results as $row) {
            $postId = $row->postid;
            $groupId = trim($row->groupid);
            $title = $this->getPostTitle($postId);
            if (trim($title) === '') {
                continue;
            }
            if ((new RightsService())->checkFileAccess($groupid, $postId)) {
                $url = route('post.index', ['groupid' => $groupId, 'pid' => $postId]);
                $postauthor = $this->getPostAuthor($postId);
                $postaliasname = (new User())->getAliasNameByID($postauthor);
                $postdate = $this->getPostDate($postId);
                if ($row->isTrash != 0) {
                    $user = Auth::user();
                    if (!$user)
                        continue;
                    if (ConstantService::$adminHasAllRights) {

                        if (!($user->isAdmin() || $user->isAuthorOfPost($postId)))
                            continue;

                    } else {

                        if (!$user->isAuthorOfPost($postId))
                            continue;
                    }

                }
                $posts[] = ['url' => $url, 'title' => $title, 'groupid' => $groupId, 'pid' => $postId, 'postaliasname' => $postaliasname, 'postdate' => $postdate, 'bTrash' => $row->isTrash];
            }
        }
        return $posts;
    }
}

