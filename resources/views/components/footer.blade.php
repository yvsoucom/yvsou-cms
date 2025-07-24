{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
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

<footer
    class="w-full bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 py-4 transition-colors duration-300">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center">
        <!-- Left: Copyright -->
        <div class="mb-2 sm:mb-0">
            &copy; {{ date('Y') }} {{ __('footer.copyright', ['app' => config('app.name')]) }} &nbsp;&nbsp;
            Powered by <a href="https://github.com/yvsoucom/yvsou-cms" target="_blank"
                class="underline hover:text-gray-600 dark:hover:text-gray-300">{{ __('yvsou-cms') }}</a>
        </div>

        <!-- Right: Links -->
        <div class="flex flex-col sm:flex-row sm:space-x-6 sm:justify-end space-y-2 sm:space-y-0">
            <a href="{{ route('about') }}"
                class="text-sm hover:text-gray-600 dark:hover:text-gray-300 transition-colors">{{ __('footer.about') }}</a>
            <a href="{{ route('contact') }}"
                class="text-sm hover:text-gray-600 dark:hover:text-gray-300 transition-colors">{{ __('footer.contact') }}</a>
            <a href="{{ route('terms') }}"
                class="text-sm hover:text-gray-600 dark:hover:text-gray-300 transition-colors">{{ __('footer.terms') }}</a>
            <a href="{{ route('privacy') }}"
                class="text-sm hover:text-gray-600 dark:hover:text-gray-300 transition-colors">{{ __('footer.privacy') }}</a>
        </div>
    </div>
</footer>