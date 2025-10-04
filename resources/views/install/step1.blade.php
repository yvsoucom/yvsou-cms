<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025  
// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>

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

            <div>
                <label for="db_connection">{{ __('installer.Database Engine') }}:</label>
                <select name="db_connection" id="db_connection" required>
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                    <option value="sqlite">SQLite</option>
                </select>
            </div>

            <x-install.input name="db_host" label="installer.db_host" value="127.0.0.1" />
            <x-install.input name="db_port" label="installer.db_port" value="3306" />
            <x-install.input name="db_database" label="installer.db_name" />
            <x-install.input name="db_username" label="installer.db_user" />
            <x-install.input name="db_password" label="installer.db_pass" type="password" />

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
                    <label for="is_blockbot" class="inline-flex items-center">
                        <input type="checkbox" name="websocket_enabled" id="websocket_enabled" value="1"
                            class="form-checkbox text-indigo-600">
                        <span class="ml-2">{{ __('installer.websocket enabled') }}</span>
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
<script>
    document.getElementById('db_connection').addEventListener('change', function () {
        const hostInput = document.getElementById('db_host');
        const portInput = document.getElementById('db_port');
        const dbusernameInput = document.getElementById('db_username');
        const dbpasswordInput = document.getElementById('db_password');
        switch (this.value) {
            case 'mysql':
                portInput.value = 3306;
                portInput.disabled = false;
                break;
            case 'pgsql':
                portInput.value = 5432;
                portInput.disabled = false;
                break;
            case 'sqlite':
                portInput.value = '';
                portInput.disabled = true; // SQLite does not need port
                hostInput.value = ''; // SQLite does not need host
                hostInput.disabled = true;
                dbusernameInput.value = ''; // SQLite does not need username
                dbusernameInput.disabled = true;
                dbpasswordInput.value = ''; // SQLite does not need password
                dbpasswordInput.disabled = true;
                break;
        }
    });
</script>

</html>