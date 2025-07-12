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
    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- 📱 Mobile Cards -->
            <div class="sm:hidden space-y-4">
                @foreach ($allMessages as $msg)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-2">
                            <div>
                                <span class="text-xs uppercase text-gray-500">{{ __('message.msg_from') }}</span>
                                <div class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $msg->from_username ?? __('System') }}
                                </div>
                            </div>

                            <div>
                                <span class="text-xs uppercase text-gray-500">{{ __('message.msg_content') }}</span>
                                <div class="text-gray-700 dark:text-gray-200 break-words whitespace-normal">
                                    {{ $msg->msg_content }}
                                </div>
                            </div>

                            @if (isset($msg->to_domainid))
                                <div>
                                    <span class="text-xs uppercase text-gray-500">{{ __('message.msg_castgroupid') }}</span>
                                    <div class="text-gray-700 dark:text-gray-200">
                                        {{ $msg->to_domainid }}
                                    </div>
                                </div>
                            @endif

                            <div>
                                <span class="text-xs uppercase text-gray-500">{{ __('message.msg_time') }}</span>
                                <div class="text-gray-600 dark:text-gray-400">
                                    {!! ($lastReadTime && $msg->dtime > $lastReadTime)
                    ? '<span class="text-red-600 dark:text-red-400 font-semibold">' . $msg->dtime . '</span>'
                    : $msg->dtime !!}
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>

            <!-- 🖥️ Desktop Table -->
            <div class="hidden sm:block overflow-x-auto rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('message.msg_from') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('message.msg_content') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('message.msg_castgroupid') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('message.msg_time') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($allMessages as $msg)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                                {{ $msg->from_username ?? __('System') }}
                                            </td>
                                            <td class="px-4 py-2 text-gray-700 dark:text-gray-200 break-words whitespace-normal max-w-md">
                                                {{ $msg->msg_content }}
                                            </td>
                                            <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                                {{ $msg->to_domainid ?? '' }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {!! ($lastReadTime && $msg->dtime > $lastReadTime)
                            ? '<span class="text-red-600 dark:text-red-400 font-semibold">' . $msg->dtime . '</span>'
                            : '<span class="text-gray-600 dark:text-gray-400">' . $msg->dtime . '</span>' !!}
                                            </td>
                                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cancel Button -->
            <div class="mt-6">
                <a href="{{ route('home') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-sm transition">
                    {{ __('Cancel') }}
                </a>
            </div>

        </div>
    </div>

@endsection