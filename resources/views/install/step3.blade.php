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
        <form method="POST" action="{{ route('install.saveAdmin') }}">
            @csrf
            
            <!-- Admin Setup -->
            <h2 class="text-xl font-semibold mt-8 mb-4">{{ __('installer.admin_setup') }}</h2>

            <x-install.input name="name" label="installer.admin_name" required />
            <x-install.input name="email" label="installer.admin_email" type="email" required />
            <x-install.input name="password" label="installer.admin_password" type="password" required />
   <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('installer.create_admin') }}
                    </button>

                </div>
        </form>
    </div>
</body>

</html>