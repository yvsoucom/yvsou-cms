{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-26
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

@extends('layouts.app')
@section('content')
    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md transition-colors duration-200">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">{{ __('plugin.Plugin Manager') }} </h1>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800">
                        <th
                            class="p-3 text-left text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                            {{ __('plugin.Name') }}</th>
                        <th
                            class="p-3 text-left text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                            {{ __('plugin.Version') }}</th>
                        <th
                            class="p-3 text-left text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                            {{ __('plugin.Status') }}</th>
                        <th
                            class="p-3 text-left text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                            {{ __('plugin.Dependencies') }}</th>
                        <th
                            class="p-3 text-left text-gray-800 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                            {{ __('plugin.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plugins as $plugin)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
                            <td class="p-3 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                {{ $plugin['name'] }}</td>
                            <td class="p-3 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                {{ $plugin['version'] }}</td>
                            <td class="p-3 border-b border-gray-200 dark:border-gray-700">
                                <span
                                    class="{{ $plugin['enabled'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $plugin['enabled'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="p-3 border-b border-gray-200 dark:border-gray-700">
                                <pre
                                    class="text-xs bg-gray-100 dark:bg-gray-800 p-2 rounded text-gray-700 dark:text-gray-300">{{ json_encode($plugin['dependencies'], JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td class="p-3 border-b border-gray-200 dark:border-gray-700">
                                <a href="{{ route('admin.plugins.toggle', $plugin['name']) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline mr-2">
                                    {{ $plugin['enabled'] ? 'Disable' : 'Enable' }}
                                </a>
                                <a href="{{ route('admin.plugins.delete', $plugin['name']) }}"
                                    class="text-red-600 dark:text-red-400 hover:underline">
                                    {{ __('plugin.Delete') }} 
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mt-8 mb-4">{{ __('plugin.Upload Plugin')}}</h2>
        <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data"
            class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">{{ __('plugin.Plugin ZIP File')}}</label>
                <input type="file" name="plugin_zip" required
                    class="block w-full text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded transition-colors duration-200">
                {{ __('plugin.Upload ZIP')}}  
            </button>
        </form>
    </div>
@endsection