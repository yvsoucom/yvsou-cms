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
 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Laravel Install Wizard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">
  <div class="w-full max-w-4xl bg-white shadow-xl rounded-2xl p-6 sm:p-8 md:p-10 lg:p-12 mx-auto">


        <h1 class="text-3xl sm:text-4xl font-extrabold text-center text-blue-600 mb-8">Laravel Installation</h1>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('install.submit') }}" class="space-y-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700">App Name</label>
                    <input type="text" name="app_name" id="app_name" placeholder="My App"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                </div>

                <div>
                    <label for="app_url" class="block text-sm font-medium text-gray-700">App URL</label>
                    <input type="url" name="app_url" id="app_url" placeholder="https://example.com"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                </div>

                <div>
                    <label for="default_lang" class="block text-sm font-medium text-gray-700">Default Language</label>
                    <select name="default_lang" id="default_lang"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <option value="en">English</option>
                        <option value="zh">中文</option>
                        <option value="ja">日本語</option>
                        <option value="fr">Français</option>
                    </select>
                </div>

                <div>
                    <label for="language_set" class="block text-sm font-medium text-gray-700">Language Set</label>
                    <select name="lang_set[]" id="lang_set" multiple
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                        <option value="en" {{ in_array('en', old('lang_set', [])) ? 'selected' : '' }}>English
                        </option>
                        <option value="zh" {{ in_array('zh', old('lang_set', [])) ? 'selected' : '' }}>中文
                        </option>
                        <option value="ja" {{ in_array('ja', old('lang_set', [])) ? 'selected' : '' }}>日本語</option>
                        <option value="fr" {{ in_array('fr', old('lang_set', [])) ? 'selected' : '' }}>Français</option>
                  
                    </select>
                </div>
            </div>

            <hr class="border-t border-gray-200" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="db_host" class="block text-sm font-medium text-gray-700">DB Host</label>
                    <input type="text" name="db_host" id="db_host" value="127.0.0.1"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                </div>

                <div>
                    <label for="db_port" class="block text-sm font-medium text-gray-700">DB Port</label>
                    <input type="text" name="db_port" id="db_port" value="3306"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                </div>

                <div>
                    <label for="db_name" class="block text-sm font-medium text-gray-700">Database Name</label>
                    <input type="text" name="db_name" id="db_name"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                </div>

                <div>
                    <label for="db_user" class="block text-sm font-medium text-gray-700">DB Username</label>
                    <input type="text" name="db_user" id="db_user"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                </div>

                <div class="md:col-span-2">
                    <label for="db_pass" class="block text-sm font-medium text-gray-700">DB Password</label>
                    <input type="password" name="db_pass" id="db_pass"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-md shadow-md transition duration-200 ease-in-out">
                    Install Application
                </button>
            </div>
        </form>
    </div>

</body>

</html>