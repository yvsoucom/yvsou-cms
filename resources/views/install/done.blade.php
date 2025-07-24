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

 