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
         
        @foreach (get_all_plugins() as $plugin)
            <div class="card p-4 rounded shadow">

                @if (!empty($plugin['menus']))
                    @php
                        $menu = $plugin['menus'];

                        $icon = $menu['icon'] ?? '🧩';
                       // $name = $menu['name'] ?? '🧩';
                
                        $name = __($plugin['slug'] . '::menu.' . $menu['name']);

                        $route = "plugins." . $plugin['slug'] . ".index";
                        $url = route($route);
                        echo "<li>$icon <a href='{$url}'><strong>" . htmlspecialchars($name) . "</strong></a> — <code>/</code></li>";
                    @endphp
                @endif

            </div>
        @endforeach
 
    </div>

@endsection