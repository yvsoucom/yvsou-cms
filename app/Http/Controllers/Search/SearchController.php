<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
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

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Services\SearchService;
class SearchController extends Controller
{
    public function search(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'keyword':
                $likeitem = trim($request->input('keyword'));

                $postlines = (new SearchService())->getKeywordPosts($likeitem);
                return view('search.searchkeyword', compact('postlines'));

            case 'mykeyword':
                $term = $request->input('mykeyword');

                $postlines = (new SearchService())->getMyKeywordPosts($term);
                return view('search.searchmykeyword', compact('postlines'));


            case 'dir':
                $dirkey = $request->input('dir');
                // Handle directory search
                $dirlines = (new SearchService())->getKeywordDirs($dirkey);
                return view('search.searchdir', compact('dirlines'));


            case 'mydir':
                
                $dirlines = (new SearchService())->getMyALLDirs();
                return view('search.searchmydir', compact('dirlines'));

            case 'mygroup':
                  $dirlines = (new SearchService())->getMyALLGroups();
                return view('search.searchmygroup', compact('dirlines'));

               

            default:
                // Invalid action
                break;
        }

        // return view(...) or redirect()->back()->with(...)
    }
}