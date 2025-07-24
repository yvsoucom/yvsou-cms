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
    @can('admin')
        @include('version.version-banner')
    @endcan
    <div class="min-h-screen flex bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

        <div class="w-64 p-6 bg-white dark:bg-gray-800 shadow rounded-lg text-center transition-colors duration-200">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">{{ __('dashboard.dashboard') }}</h2>
            <nav class="space-y-3">
                <a href="{{ route('admin.usercenter.index') }}"
                    class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                    {{ __('dashboard.usercenter') }}
                </a>
                @can('admin')
                    <a href="{{ route('admin.plugins.index') }}"
                        class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                        {{ __('dashboard.plugins') }}
                    </a>
                    <a href="{{ route('admin.setmail.edit') }}"
                        class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                        {{ __('dashboard.mailsettings') }}
                    </a>
                    <a href="{{ route('admin.setcustomconfig.edit') }}"
                        class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                        {{ __('dashboard.custompagesettings') }}
                    </a>
                    <a href="{{ route('admin.castmsg.edit') }}"
                        class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                        {{ __('dashboard.castmsg') }}
                    </a>
                @endcan
            </nav>
        </div>
    </div>
@endsection