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
    <div class="flex h-screen items-center justify-center bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-colors duration-200">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-gray-800 dark:text-gray-200" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('auth.email')" class="dark:text-gray-300" />
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('auth.password')" class="dark:text-gray-300" />
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 bg-white dark:bg-gray-700"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('auth.remember_me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-150"
                            href="{{ route('password.request') }}">
                            {{ __('auth.forgot_password') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-blue-500">
                        {{ __('auth.login') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>