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
use App\Models\DomainUserSearchKey;
use App\Models\DomainSearchKey;
use App\Models\DomainSearchDir;
use Illuminate\Support\Facades\Auth;

class SearchUrlService
{
    function searchMyKeywordRecord(string $keyword)
    {
        $user = Auth::user();
        if (!$user) {
            return; // no logged-in user
        }

        $lang = app()->getLocale(); // or however you get current lang in your app
        $userId = $user->id;
        $keyword = trim($keyword);

        if ($keyword === '') {
            return;
        }

        // Get all keywords for user/lang ordered by numbers desc

        $maxRecord = DomainUserSearchKey::where('userid', $userId)
            ->where('lang', $lang)
            ->orderByDesc('numbers')
            ->first();
        return $maxRecord->keyword;
    }


    function searchKeywordRecord(string $keyword)
    {
        $user = Auth::user();
        if (!$user) {
            return; // no logged-in user
        }

        $lang = app()->getLocale(); // or however you get current lang in your app

        $keyword = trim($keyword);

        if ($keyword === '') {
            return;
        }

        // Get all keywords for user/lang ordered by numbers desc

        $maxRecord = DomainSearchKey::where('lang', $lang)
            ->orderByDesc('numbers')
            ->first();
        return $maxRecord->keyword;
    }

    function updateMyKeywordRecord(string $keyword)
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $lang = app()->getLocale(); // or however you get current lang in your app
        $userId = $user->id;
        $keyword = trim($keyword);

        if ($keyword === '') {
            return;
        }

        DomainUserSearchKey::where('userid', $userId)
            ->where('lang', $lang)
            ->where('keyword', $keyword)
            ->increment('numbers');

    }


    function updateKeywordRecord(string $keyword)
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $lang = app()->getLocale(); // or however you get current lang in your app
        $userId = $user->id;
        $keyword = trim($keyword);

        if ($keyword === '') {
            return;
        }

        DomainSearchKey::where('lang', $lang)
            ->where('keyword', $keyword)
            ->increment('numbers');

    }




    function updateDirsRecord(string $directory)
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $lang = app()->getLocale(); // or however you get current lang in your app
        $userId = $user->id;
        $directory = trim($directory);

        if ($directory === '') {
            return;
        }

        DomainSearchDir::where('userid', $userId)
            ->where('lang', $lang)
            ->where('directory', $directory)
            ->increment('numbers');

    }

    function searchDirRecord(string $directory)
    {
        $user = Auth::user();
        if (!$user) {
            return;  
        }

        $lang = app()->getLocale(); // or however you get current lang in your app

        $directory = trim($directory);

        if ($directory === '') {
            return;
        }

        // Get all keywords for user/lang ordered by numbers desc

        $maxRecord = DomainSearchDir::where('lang', $lang)
            ->orderByDesc('numbers')
            ->first();
        return $maxRecord->directory;
    }

}