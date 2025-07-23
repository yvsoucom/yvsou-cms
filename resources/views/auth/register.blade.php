{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-26
* License: Dual Licensed – GPLv3 or Commercial
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* As an alternative to GPLv3, commercial licensing is available for organizations
* or individuals requiring proprietary usage, private modifications, or support.
*
* Contact: yvsoucom@gmail.com
* GPL License: https://www.gnu.org/licenses/gpl-3.0.html
*/
--}}

<x-guest-layout>
    <div class="flex h-screen items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded shadow">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label class="block mt-1 w-full dark:bg-gray-800 dark:text-white" for="name"
                        :value="__('auth.account')" />
                    <x-text-input id="name" class="block mt-1 w-full dark:bg-gray-700 dark:text-white" type="text"
                        name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label class="block mt-1 w-full dark:bg-gray-800 dark:text-white" for="email"
                        :value="__('auth.email')" />
                    <x-text-input id="email" class="block mt-1 w-full dark:bg-gray-700 dark:text-white" type="email"
                        name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label class="block mt-1 w-full dark:bg-gray-800 dark:text-white" for="password"
                        :value="__('auth.password')" />

                    <x-text-input id="password" class="block mt-1 w-full dark:bg-gray-700 dark:text-white"
                        type="password" name="password" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label class="block mt-1 w-full dark:bg-gray-800 dark:text-white"
                        for="password_confirmation" :value="__('auth.confirmpassword')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full dark:bg-gray-700 dark:text-white"
                        type="password" name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        href="{{ route('login') }}">
                        {{ __('auth.alreadyregistered') }}
                    </a>

                    <x-primary-button class="ms-4">
                        {{ __('auth.register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>