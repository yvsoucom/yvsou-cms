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


    <div class="min-h-screen flex flex-col gap-4 bg-gray-50 dark:bg-gray-900 transition-colors duration-200 p-4">
        @php
            $locale = app()->getLocale(); // 'en', 'zh', 'ja', etc.
        @endphp

        @foreach (get_all_plugins() as $plugin)
            <div x-data="{ open: false }" class="card p-4 rounded shadow bg-white dark:bg-gray-800">
                <!-- Plugin Header -->
                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        {{ $plugin['name'][$locale] ?? $plugin['name']['en'] ?? $plugin['slug'] }}
                    </h3>

                    <button class="text-sm text-blue-500 hover:underline">
                        <span x-show="!open">Show</span>
                        <span x-show="open">Hide</span>
                    </button>
                </div>

                <!-- Plugin Details -->
                <div x-show="open" x-transition class="mt-2 text-gray-700 dark:text-gray-200">
                    <p><strong>Slug:</strong> {{ $plugin['slug'] }}</p>
                    <p><strong>Version:</strong> {{ $plugin['version'] }}</p>
                    <p><strong>Status:</strong>
                        @if ($plugin['enabled'])
                            <span class="text-green-500">Enabled</span>
                        @else
                            <span class="text-red-500">Disabled</span>
                        @endif
                    </p>

                    @if (!empty($plugin['shortcodes']))
                        <p class="mt-2"><strong>Shortcodes:</strong></p>
                        <ul class="list-disc list-inside">
                            @foreach ($plugin['shortcodes'] as $tag => $fn)
                                <li><code>[{{ $tag }}]</code> → <code>{{ $fn }}</code></li>
                            @endforeach
                        </ul>
                    @endif

                    @if (!empty($plugin['menus']))
                        <p class="mt-2"><strong>menus:</strong></p>
                        @php
                            $menu = $plugin['menus'];

                            $icon = $menu['icon'] ?? '🧩';
                            $name = $menu['name'] ?? '🧩';
                            $route = "plugins." . $plugin['slug']. ".index";
                            $url = route($route);
                            echo "<li>$icon <a href='{$url}'><strong>" . htmlspecialchars($name) . "</strong></a> — <code>/</code></li>";
                        @endphp
                    @endif
                </div>
            </div>
        @endforeach
    </div>

@endsection