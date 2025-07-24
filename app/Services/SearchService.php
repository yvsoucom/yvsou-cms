<?php
/**
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-16
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


namespace App\Services;
use App\Models\DomainPostId;
use App\Models\DomainManager;
use Illuminate\Support\Carbon;
use App\Services\LocaleService;
use App\Services\RightsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class SearchService
{
    public function getKeywordPosts($keyword): array
    {
        $langid = (new LocaleService())->getcurlang();
        $results = $this->getpostfromkeys($keyword);
        $postlines = [];
        foreach ($results as $item) {
            logger("getKeywordPosts item", [$item]);
            logger("getKeywordPosts id", [$item->id]);
            $postsQuery = DB::table('domain_post_ids')
                ->select('postid', 'lang', 'groupid')
                ->where('isTrash', 0)
                ->where('postid', $item->id)
                ->where('lang', $langid)
                ->get();
            foreach ($postsQuery as $row) {
                logger("getKeywordPosts row", [$row]);
                $postId = $row->postid;
                $groupId = trim($row->groupid);
                $title = (new PostService())->getPostTitle($postId);
                if (trim($title) === '') {
                    continue;
                }
                if ((new RightsService())->checkFileAccess($groupId, $postId)) {
                    $url = route('post.index', ['groupid' => $groupId, 'pid' => $postId]);
                    $postlines[] = ['url' => $url, 'title' => $title, 'groupid' => $groupId, 'postid' => $postId];
                }
            }
        }
        return $postlines;
    }

    public function getMyKeywordPosts($keyword): array
    {
        $langid = (new LocaleService())->getcurlang();
        $results = $this->getmypostfromkeys($keyword);
        $postlines = [];
        foreach ($results as $item) {
            logger("getKeywordPosts item", [$item]);
            logger("getKeywordPosts id", [$item->id]);
            $postsQuery = DB::table('domain_post_ids')
                ->select('postid', 'lang', 'groupid')
                ->where('isTrash', 0)
                ->where('postid', $item->id)
                ->where('lang', $langid)
                ->get();
            foreach ($postsQuery as $row) {
                logger("getKeywordPosts row", [$row]);
                $postId = $row->postid;
                $groupId = trim($row->groupid);
                $title = (new PostService())->getPostTitle($postId);
                if (trim($title) === '') {
                    continue;
                }
                if ((new RightsService())->checkFileAccess($groupId, $postId)) {
                    $url = route('post.index', ['groupid' => $groupId, 'pid' => $postId]);
                    $postlines[] = ['url' => $url, 'title' => $title, 'groupid' => $groupId, 'postid' => $postId];
                }
            }
        }
        return $postlines;
    }

    function getpostfromkeys($likeitem)
    {

        $query = DB::table('domain_posts')
            ->where(function ($q) use ($likeitem) {
                $q->where('post_title', 'like', "%{$likeitem}%")
                    ->orWhere('post_content', 'like', "%{$likeitem}%");
            })->limit(200)->get();

        return $query;
    }

    function getmypostfromkeys($likeitem)
    {
        $currentUserId = Auth::id();
        $query = DB::table('domain_posts')
            ->where('post_author', $currentUserId)
            ->where(function ($q) use ($likeitem) {
                $q->where('post_title', 'like', "%{$likeitem}%")
                    ->orWhere('post_content', 'like', "%{$likeitem}%");
            })->limit(200)->get();

        return $query;
    }

    public function getDomainIdsFromDirkey($likeitem, $lang)
    {
        $domainName = str_replace(' ', '', strtoupper(trim($likeitem)));

        $results = DB::table('domain_trees')
            ->select('id')
            ->where('lang', $lang)
            ->whereRaw('REPLACE(UPPER(TRIM(domain_dict_name)), " ", "") LIKE ?', ["%$domainName%"])
            ->limit(200)
            ->pluck('id');

        return $results->toArray(); // returns plain array
    }

    public function getKeywordDirs($keyword): array
    {
        $langid = (new LocaleService())->getcurlang();
        $results = $this->getDomainIdsFromDirkey($keyword, $langid);
        logger("getKeywordDirs", $results);
        $dirlines = [];
        $rightsService = new RightsService();
        $domainService = new DomainService();
        foreach ($results as $dictid) {
            $likestringtmp = '%.' . $dictid . '.%';
            $likestringtmp2 = '%.' . $dictid; // for cases ending in domainid
            $items = DomainManager::where('bTrash', 0)
                ->where(function ($query) use ($likestringtmp, $likestringtmp2) {
                    $query->whereRaw('TRIM(domainid) LIKE ?', [$likestringtmp])
                        ->orWhereRaw('TRIM(domainid) LIKE ?', [$likestringtmp2]);
                })
                ->get(['domainid']);
            foreach ($items as $item) {
                $domainId = $item['domainid'];
                if (trim($domainId) == "")
                    continue;
                if ($rightsService->checkRightPermission($domainId, 'SHOWDIR')) {
                    $url = route('post.postview', ['groupid' => urlencode($domainId)]);
                    $title = $domainService->get_jointitle_by_uniqid($domainId);
                    $dirlines[] = [
                        'url' => $url,
                        'title' => $title
                    ];
                }
            }
        }
        //  dd($dirlines);
        return $dirlines;
    }

    public function getMyALLDirs(): array
    {
        $langid = (new LocaleService())->getcurlang();

        $dirlines = [];
        $rightsService = new RightsService();
        $domainService = new DomainService();

        $currentUserId = Auth::id();

        $items = DomainManager::where('userid', $currentUserId)
            ->limit(200)
            ->get(['domainid']);
        foreach ($items as $item) {
            $domainId = $item['domainid'];
            if (trim($domainId) == "")
                continue;
            $url = route('post.postview', ['groupid' => urlencode($domainId)]);
            $title = $domainService->get_jointitle_by_uniqid($domainId);
            $dirlines[] = [
                'url' => $url,
                'title' => $title
            ];

        }
        //  dd($dirlines);
        return $dirlines;
    }

    public function getMyALLGroups(): array
    {
        $langid = (new LocaleService())->getcurlang();
        $dirlines = [];
        $rightsService = new RightsService();
        $domainService = new DomainService();
        $currentUserId = Auth::id();
        $items = DB::table('domain_names')
            ->where('userid', $currentUserId)
            ->where('checked', 0)
            ->pluck('domainid');
        foreach ($items as $domainId) {
            if (trim($domainId) == "")
                continue;
            $url = route('post.postview', ['groupid' => urlencode($domainId)]);
            $title = $domainService->get_jointitle_by_uniqid($domainId);
            $dirlines[] = [
                'url' => $url,
                'title' => $title
            ];

        }
        //  dd($dirlines);
        return $dirlines;
    }

}

