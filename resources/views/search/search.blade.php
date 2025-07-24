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
 

<form method="POST" action="{{ route('search.search') }}"
    class="max-w-md mx-auto space-y-6 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
    @csrf
 
    <!-- Keyword Row -->
    <div class="space-y-2">
        <label for="keyword" class="block text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('search.keywordsearch') }}</label>
        <div class="flex flex-wrap items-center gap-2">
            <input type="text" name="keyword" id="keyword" placeholder="{{ __('search.inputkeyword') }}"
                class="flex-1 w-full p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200">
            <button type="submit" name="action" value="keyword"
                class="px-5 py-3 w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
                Go
            </button>
        </div>
    </div>

    @auth
        <!-- My Keyword Row -->
        <div class="space-y-2">
            <label for="mykeyword"
                class="block text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('search.personalkeyword') }}</label>
            <div class="flex flex-wrap items-center gap-2">
                <input type="text" name="mykeyword" id="mykeyword" placeholder="{{ __('search.inputpersonalkeyword') }}"
                    class="flex-1 w-full p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/30 focus:border-green-500 dark:focus:border-green-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                <button type="submit" name="action" value="mykeyword"
                    class="px-5 py-3 w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Go
                </button>
            </div>
        </div>
    @endauth

    <!-- Directory Row -->
    <div class="space-y-2">
        <label for="dir" class="block text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('search.directorysearch') }}</label>
        <div class="flex flex-wrap items-center gap-2">
            <input type="text" name="dir" id="dir" placeholder="{{ __('search.inputdirectory') }}"
                class="flex-1 w-full p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900/30 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200">
            <button type="submit" name="action" value="dir"
                class="px-5 py-3 w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                </svg>
                Go
            </button>
        </div>
    </div>

    @auth
        <!-- Bottom Actions: All Directories & All Groups -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
            <button type="submit" name="action" value="mydir"
                class="w-full px-4 py-3 bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                    <path fill-rule="evenodd" d="M8 11a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                {{ __('search.alldirectory') }}
            </button>

            <button type="submit" name="action" value="mygroup"
                class="w-full px-4 py-3 bg-gradient-to-br from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                {{ __('search.allgroup') }}
            </button>
        </div>
    @endauth
</form>
 