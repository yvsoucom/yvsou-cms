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
    <main class="min-h-screen py-6 mt-6 text-left dark:bg-gray-900 dark:text-gray-200">

        <div class="w-full px-4 sm:px-6 lg:px-8"> <!-- Responsive padding -->
            <h1 class="text-2xl font-bold dark:text-gray-100 break-words max-w-full overflow-hidden">
                {{ __('postview.postview4domain') }} ({{ $groupid }})
            </h1>

            <div class="mt-4 dark:text-gray-300 w-full">
                <div class="flex flex-wrap gap-x-1 gap-y-1 break-all">
                    {!! $domain_links !!}
                </div>
            </div>

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