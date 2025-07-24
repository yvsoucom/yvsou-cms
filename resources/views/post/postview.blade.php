{{--
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-26
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
<main class="min-h-screen py-6 mt-6 text-left dark:bg-gray-900 dark:text-gray-200">

    <div class="w-full px-4 sm:px-6 lg:px-8"> <!-- Responsive padding -->
        <h1 class="text-2xl font-bold mb-4 dark:text-gray-100">{{ __('postview.postview4domain') }} {{ $groupid }}</h1>
        <div class="dark:text-gray-300 text-sm sm:text-base">{!! $domain_links !!}</div>
        <br class="hidden sm:block"> <!-- Hide break on mobile -->
        <div class="mb-4 dark:text-gray-300 grid grid-cols-1 sm:grid-cols-2 gap-2">
            <p>{{ __('postview.postnumbers') }} {{ $postnumbers }}</p>
            <p>{{ __('postview.postaLLnumbers') }} {{ $postallnumbers }}</p>
        </div>
        <form method="POST" action="{{ route('toggle.alist') }}" class="mb-6">
            @csrf
            <button type="submit"
                class="w-full sm:w-auto px-4 py-2 rounded-md font-semibold shadow-sm transition-colors duration-200
                {{ $alist ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200' }}">
                {{ $alist ? 'ALIST: ON' : 'ALIST: OFF' }}
            </button>
        </form>
        <ul class="list-disc pl-6 mt-6 space-y-2 dark:text-gray-300">
            @foreach ($posts as $item)
                <li class="dark:text-gray-300 flex flex-col sm:flex-row sm:items-baseline">
                    <a href="{{ $item['url'] }}" class="text-blue-600 dark:text-blue-400 hover:underline sm:mr-2">
                        {{ $item['title'] }}
                    </a>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        by {{ $item['postaliasname'] }} | {{ $item['postdate'] }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>  
</main>
@endsection 