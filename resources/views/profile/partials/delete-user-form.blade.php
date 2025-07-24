{{--
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-26
*
* SPDX-License-Identifier: GPL-3.0-or-later
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
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('profile.delaccount') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('profile.comfirmdel') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="dark:bg-red-700 dark:hover:bg-red-600"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('admin.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('profile.suredel') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('profile.comfirmdelpasswd') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('profile.password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600 dark:text-red-400" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button 
                    x-on:click="$dispatch('close')"
                    class="dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200"
                >
                    {{ __('profile.cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 dark:bg-red-700 dark:hover:bg-red-600">
                    {{ __('profile.delaccount') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>  