{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-07-10
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
    <div class="container mx-auto p-4 dark:bg-gray-900 dark:text-gray-200">
        <h1 class="text-2xl font-bold mb-6 dark:text-gray-100">{{__('right.Manage Rights for Post')}}: {{ $post->id }} {{__('right.in Group')}} {{ $groupid }}</h1>

        @if (session('success'))
            <div class="bg-green-200 dark:bg-green-800 dark:text-green-200 p-4 my-4 rounded">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="text-left px-4 py-3 dark:text-gray-300">{{__('right.Role')}}</th>
                        <th class="text-left px-4 py-3 dark:text-gray-300">{{__('right.Audit')}}</th>
                        <th class="text-left px-4 py-3 dark:text-gray-300">{{__('right.Read')}}</th>
                        <th class="text-left px-4 py-3 dark:text-gray-300">{{__('right.Write')}}</th>
                        <th class="text-left px-4 py-3 dark:text-gray-300">{{__('right.Execute')}}</th>
                        <th class="text-left px-4 py-3 dark:text-gray-300">{{__('right.action')}}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($rights as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <form method="POST" action="{{ route('post.file-rights.update', $post->id) }}">
                                @csrf
                                <input type="hidden" name="groupid" value="{{ $groupid }}">
                                <input type="hidden" name="role_key" value="{{ $role['key'] }}">
                                <input type="hidden" name="charorder" value="{{ $role['index'] }}">

                                <td class="p-4 dark:text-gray-300">{{__('right.'.$role['label'])}}</td>

                                @php
                                    $value = hexdec($role['value'] ?? '0');
                                @endphp

                                <td class="p-4">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" name="maudit" value="1" {{ ($value >> 3) & 1 ? 'checked' : '' }} 
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="maudit" value="0" {{ ((($value >> 3) & 1) == 0) ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">No</span>
                                    </label>
                                </td>
                                <td class="p-4">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" name="mread" value="1" {{ ($value >> 2) & 1 ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="mread" value="0" {{ ((($value >> 2) & 1) == 0) ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">No</span>
                                    </label>
                                </td>
                                <td class="p-4">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" name="mwrite" value="1" {{ ($value >> 1) & 1 ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="mwrite" value="0" {{ ((($value >> 1) & 1) == 0) ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">No</span>
                                    </label>
                                </td>
                                <td class="p-4">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" name="mexecute" value="1" {{ ($value >> 0) & 1 ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="mexecute" value="0" {{ ((($value >> 0) & 1) == 0) ? 'checked' : '' }}
                                               class="text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700">
                                        <span class="ml-2 dark:text-gray-300">No</span>
                                    </label>
                                </td>
                                <td class="p-4">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded transition-colors">
                                        {{__('right.save')}}
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection 