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

@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        @php
            $locale = app()->getLocale(); // 'en', 'zh', 'ja', etc.
        @endphp

        @foreach (get_all_plugins() as $plugin)
            <div class="card p-4 rounded shadow">
                <h3 class="text-xl font-bold">
                    {{ $plugin['name'][$locale] ?? $plugin['name']['en'] ?? $plugin['slug'] }}
                </h3>

                <p>Slug: {{ $plugin['slug'] }}</p>
                <p>Version: {{ $plugin['version'] }}</p>
                <p>Status:
                    @if ($plugin['enabled'])
                        <span class="text-green-500">Enabled</span>
                    @else
                        <span class="text-red-500">Disabled</span>
                    @endif
                </p>

                <p class="mt-2">Shortcodes:</p>
                <ul class="list-disc list-inside">
                    @foreach ($plugin['shortcodes'] as $tag => $fn)
                        <li><code>[{{ $tag }}]</code> → <code>{{ $fn }}</code></li>
                    @endforeach
                </ul>
            </div>
        @endforeach

    </div>
@endsection