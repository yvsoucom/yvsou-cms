{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-08
*
* SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
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

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('installer.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl bg-white p-8 rounded shadow">
        <h1 class="text-3xl font-bold mb-6 text-center">{{ __('installer.title') }}</h1>

        <form method="POST" action="{{ route('install.saveEnv') }}">
            @csrf

            <!-- Environment Setup -->
            <h2 class="text-xl font-semibold mb-4">{{ __('installer.env_setup') }}</h2>

            <x-install.input name="app_name" label="installer.app_name" value="yvsou-cms" />
            <x-install.input name="app_url" label="installer.app_url" value="http://127.0.0.1:8000" />
            <x-install.input name="db_host" label="installer.db_host" value="127.0.0.1" />
            <x-install.input name="db_port" label="installer.db_port" value="3306" />
            <x-install.input name="db_name" label="installer.db_name" />
            <x-install.input name="db_user" label="installer.db_user" />
            <x-install.input name="db_pass" label="installer.db_pass" type="password" />

            <!-- Admin Setup -->
            <h2 class="text-xl font-semibold mt-8 mb-4">{{ __('installer.admin_setup') }}</h2>

            <x-install.input name="name" label="installer.admin_name" required />
            <x-install.input name="email" label="installer.admin_email" type="email" required />
            <x-install.input name="password" label="installer.admin_password" type="password" required />

            <!-- Custom Config -->
            <h2 class="text-xl font-semibold mt-8 mb-4">{{ __('installer.custom_config') }}</h2>
            <div class="space-y-6">
                <div class="mb-4">
                    <label for="is_adminsp" class="inline-flex items-center">
                        <input type="checkbox" name="is_adminsp" id="is_adminsp" value="1"
                            class="form-checkbox text-indigo-600">
                        <span class="ml-2">{{ __('installer.admin_super') }}</span>
                    </label>
                </div>
                <br />
                 <div class="mb-4">
                    <label for="is_blockbot" class="inline-flex items-center">
                        <input type="checkbox" name="is_blockbot" id="is_blockbot" value="1"
                            class="form-checkbox text-indigo-600">
                        <span class="ml-2">{{ __('installer.blockbot') }}</span>
                    </label>

                </div>
                <br />
                <div class="mb-4">
                    <label for="default_lang" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('installer.choose_default_language') }}
                    </label>
                    <select name="default_lang" id="default_lang"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        <option value="en">🇺🇸 English</option>
                        <option value="zh">🇨🇳 中文</option>
                        <option value="ja">🇯🇵 日本語</option>
                        <option value="fr">🇫🇷 Français</option>
                    </select>
                </div>
                <br />
                <div class="mb-4">
                    <label for="lang_set" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('installer.choose_languages') }}
                    </label>
                    <select name="lang_set[]" id="lang_set" multiple
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm h-40">
                        <option value="en">🇺🇸 English</option>
                        <option value="zh">🇨🇳 中文</option>
                        <option value="ja">🇯🇵 日本語</option>
                        <option value="fr">🇫🇷 Français</option>
                    </select>
                </div>
                <br />
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('installer.create_config') }}
                    </button>

                </div>
            </div>
        </form>
    </div>
</body>

</html>