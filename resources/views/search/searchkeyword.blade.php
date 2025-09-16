<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>

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