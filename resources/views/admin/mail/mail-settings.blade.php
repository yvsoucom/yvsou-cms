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
<x-guest-layout>
    <div class="flex h-screen items-center justify-center bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-colors duration-200">

            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">{{ __('mail.Mail Settings') }}</h1>

            @if(session('success'))
                <div
                    class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 p-4 rounded mb-4 transition-colors duration-200">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.setmail.update') }}" class="space-y-4">
                @csrf

                @foreach(['host', 'port', 'encryption', 'username', 'password', 'from_address', 'from_name'] as $field)
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 capitalize mb-1">

                            {{ __('mail.' . str_replace("_", " ", $field)) }}
                        </label>
                        <input type="{{ $field === 'password' ? 'password' : 'text' }}" name="{{ $field }}"
                            value="{{ $settings[$field] ?? '' }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>
                @endforeach

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('mail.Save Settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>