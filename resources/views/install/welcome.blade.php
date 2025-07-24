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
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <title>{{ __('installer.title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">

    <div class="container mx-auto py-12 text-center">

        <!-- 🌐 Language Switcher -->
        <form method="POST" action="{{ route('install.setLocale') }}" class="mb-6">
            @csrf
            <label for="locale" class="block text-gray-700 font-semibold mb-2">{{ __('installer.choose_lang') }}</label>
            <select name="locale" id="locale" onchange="this.form.submit()" class="p-2 border rounded">
                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                <option value="zh" {{ app()->getLocale() === 'zh' ? 'selected' : '' }}>简体中文</option>
                <option value="ja" {{ app()->getLocale() === 'ja' ? 'selected' : '' }}>日本語</option>
                <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>Français</option>
            </select>
        </form>

        <h1 class="text-4xl font-bold mb-6">{{ __('installer.welcome') }}</h1>
        <p class="text-lg mb-8">{{ __('installer.description') }}</p>

        @if (session('message'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <a href="{{ route('install.envForm') }}"
            class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
            {{ __('installer.start') }}
        </a>
    </div>

</body>

</html>

 