{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-15
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

@extends('layouts.app')

@section('content')
    @can('admin')
        @include('version.version-banner')
    @endcan
    <div class="min-h-screen flex bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

        <div class="w-64 p-6 bg-white dark:bg-gray-800 shadow rounded-lg text-center transition-colors duration-200">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">{{ __('dashboard.dashboard') }}</h2>
            <nav class="space-y-3">
                <a href="{{ route('admin.usercenter.index') }}" 
                   class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                   {{ __('dashboard.usercenter') }}
                </a>
                @can('admin')
                    <a href="{{ route('admin.plugins.index') }}" 
                       class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                       {{ __('dashboard.plugins') }}
                    </a>
                    <a href="{{ route('admin.setmail.edit') }}" 
                       class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                       {{ __('dashboard.mailsettings') }}
                    </a>
                    <a href="{{ route('admin.setcustomconfig.edit') }}" 
                       class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                       {{ __('dashboard.custompagesettings') }}
                    </a>
                    <a href="{{ route('admin.castmsg.edit') }}" 
                       class="block text-gray-800 dark:text-gray-300 hover:font-semibold hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150">
                       {{ __('dashboard.castmsg') }}
                    </a>
                @endcan
            </nav>
        </div>
    </div>
@endsection 