{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-16
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
--}}

@extends('layouts.app')
@section('content')
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Found articles</h2>

        <!-- Scrollable container -->
        <div
            class="max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 shadow-sm dark:shadow-gray-700/30 transition-colors duration-200">
            <ul class="space-y-3">
                <p class="text-gray-600 dark:text-gray-400 mb-2">Found {{ count($postlines) }} items</p>
                @foreach ($postlines as $item)
                    <li class="hover:bg-gray-50 dark:hover:bg-gray-700/50 px-2 py-1 rounded transition-colors">
                        <a href="{{ $item['url'] }}"
                            class="text-blue-600 dark:text-blue-400 hover:underline hover:text-blue-800 dark:hover:text-blue-300">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection