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