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
<x-guest-layout>
    <div class="flex h-screen items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded shadow">

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                {{ __('auth.verifyemail') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('auth.verifyemaillink') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-primary-button class="dark:bg-indigo-600 dark:hover:bg-indigo-700">
                            {{ __('auth.resendverifyemail') }}
                        </x-primary-button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        {{ __('auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>