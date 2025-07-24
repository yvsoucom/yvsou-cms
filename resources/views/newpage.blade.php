<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
 
@php
    $items = app(\App\Services\PagelineService::class)->showNewPosts();
@endphp
 
 
<div class="bg-white dark:bg-gray-800">
    <h2 class="text-xl font-semibold mb-3 dark:text-gray-100">
        {{ __('headline.newposts')}}
    </h2>

    <!-- Scrollable container -->
    <div class="max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3 bg-white dark:bg-gray-800 shadow-sm dark:shadow-gray-700/50">
        <ul class="space-y-2">
            @foreach ($items as $item)
                <li>
                    <a href="{{ $item['url'] }}" 
                       class="text-blue-600 dark:text-blue-400 hover:underline transition-colors">
                        {{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>