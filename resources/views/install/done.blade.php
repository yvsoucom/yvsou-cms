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
<html>

<head>
    <title>{{ __('installer.install_complete_title') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8fafc;
        }

        .card {
            max-width: 600px;
            margin: 4rem auto;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <div class="card p-4">
        <h2 class="mb-3 text-center">✅ {{ __('installer.install_complete_title') }}</h2>
        <p class="text-center">{{ __('installer.install_complete_message') }}</p>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-success">{{ __('installer.goto_home') }}</a>
            <a href="{{ route('login')  }}" class="btn btn-primary">{{ __('installer.goto_admin') }}</a>
        </div>

        <p class="mt-4 text-muted text-center small">{{ __('installer.security_note') }}</p>
    </div>
</body>

</html>

 