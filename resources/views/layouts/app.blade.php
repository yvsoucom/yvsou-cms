{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-04
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

<!DOCTYPE html>
<!-- #endregion -->
 
<html lang="{{ str_replace('_', '-', App::getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>yvsou.com</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="{{ asset('css/fallback.css') }}" rel="stylesheet">
    @endif

    @stack('styles')

</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] p-6 lg:p-8 min-h-screen">

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if(!session('human_verified'))
        @include('components.guest-verification')
    @endif

    @include('components.header')
    <div class="mt-8"></div> {{-- Adds 2rem (32px) of vertical space --}}

    <div class="min-h-[200px]">
        @if(config('yvsou_config.BLOCKBOT'))
            @if(session('human_verified') || Auth::check())
                @yield('content')
            @else
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <p>Please complete human verification above to view content.</p>
                </div>
            @endif
        @else
            @yield('content')
        @endif
    </div>


    <div class="mt-8"></div> {{-- Space between content and footer --}}
    @include('components.footer')
    <!-- Page-specific scripts -->


    @stack('scripts')
</body>

</html>