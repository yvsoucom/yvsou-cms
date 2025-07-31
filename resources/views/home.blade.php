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

{{-- resources/views/home.blade.php --}}

@extends('layouts.app')
 
@section('content')
    <div class="p-2 md:p-8 text-gray-900 dark:text-gray-200">
       
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Left panel: scrollable on desktop --}}
            <div class="space-y-2 md:col-span-1 md:h-[calc(100vh-6rem)] md:overflow-y-auto md:sticky md:top-16">
                {{-- Search Box --}}
                <div class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-700/50 rounded-2xl p-4">
                    @include('search.search')
                </div>

                {{-- Domain Tree --}}
                <div class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-700/50 rounded-2xl p-4 overflow-x-auto">
                    <h2 class="text-lg md:text-xl font-semibold mb-4 dark:text-gray-100">{{__('domain.domaintree')}}</h2>
                    @livewire('show-domain-tree')
                </div>
            </div>

            {{-- Right panel --}}
            <div class="space-y-4 md:col-span-2">
                {{-- New Page --}}
                <div class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-700/50 rounded-2xl p-4">
                    @include('newpage')
                </div>

                {{-- New Directory --}}
                <div class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-700/50 rounded-2xl p-4">
                    @include('newdir')
                </div>
            </div>
        </div>
    </div>
 
@endsection