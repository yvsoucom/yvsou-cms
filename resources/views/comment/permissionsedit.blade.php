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
@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-4 dark:bg-gray-900 dark:text-gray-200">
        <h1 class="text-2xl font-bold mb-6 dark:text-gray-100">{{__('right.Manage Rights for Comment')}} : {{ $post->id }} {{__('right.in Group')}} {{ $groupid }}</h1>

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
                            <form method="POST" action="{{ route('post.comment-rights.update', $post->id) }}">
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