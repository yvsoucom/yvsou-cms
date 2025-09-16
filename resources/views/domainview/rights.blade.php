<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

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
    <div class="container">
        <h1 class="text-2xl font-bold my-4 dark:text-white">{{__('right.editdomainright')}}{{ $groupid }}</h1>
        @if (session('success'))
            <div class="bg-green-200 dark:bg-green-800 p-2 my-2 rounded">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="text-left px-4 py-2 dark:text-white">{{__('right.Role')}}</th>
                        <th class="text-left px-4 py-2 dark:text-white">{{__('right.writedir')}}</th>
                        <th class="text-left px-4 py-2 dark:text-white">{{__('right.readdir')}}</th>
                        <th class="text-left px-4 py-2 dark:text-white">{{__('right.adddir')}}</th>
                        <th class="text-left px-4 py-2 dark:text-white">{{__('right.showdir')}}</th>
                        <th class="text-left px-4 py-2 dark:text-white">{{__('right.action')}}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($rights as $role)
                        <tr class="border-t dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <form method="POST" action="{{ route('domainview.rights.update', $groupid) }}">
                                @csrf
                                <input type="hidden" name="groupid" value="{{ $groupid }}">
                                <input type="hidden" name="role_key" value="{{ $role['key'] }}">

                                <td class="p-2 dark:text-white">{{__('right.' . $role['label'])}}</td>

                                @php
                                    $value = $role['value'];
                                @endphp

                                <td class="p-2">
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="write" value="1" {{ ($value >> 3) & 1 ? 'checked' : '' }}>Yes
                                    </label>
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="write" value="0" {{ ((($value >> 3) & 1) == 0) ? 'checked' : '' }}>No
                                        <!-- #region --> </label>
                                </td>
                                <td class="p-2">
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="read" value="1" {{ ($value >> 2) & 1 ? 'checked' : '' }}>Yes
                                    </label>
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="read" value="0" {{ ((($value >> 2) & 1) == 0) ? 'checked' : '' }}>No
                                        <!-- #region --> </label>
                                </td>
                                <td class="p-2">
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="addchild" value="1" {{ ($value >> 1) & 1 ? 'checked' : '' }}>Yes
                                        <!-- #region --> </label>
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="addchild" value="0" {{ ((($value >> 1) & 1) == 0) ? 'checked' : '' }}>No
                                        <!-- #region --> </label>
                                </td>
                                <td class="p-2">
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="showdir" value="1" {{ ($value >> 0) & 1 ? 'checked' : '' }}>Yes
                                        <!-- #region --> </label>
                                    <label class="inline-flex items-center dark:text-white">
                                        <input type="radio" name="showdir" value="0" {{ ((($value >> 0) & 1) == 0) ? 'checked' : '' }}>No
                                        <!-- #region --> </label>
                                </td>
                                <td class="p-2">
                                    <button type="submit"
                                        class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded transition duration-200 dark:bg-blue-600 dark:hover:bg-blue-700">{{__('right.save')}}</button>
                                </td>
                            </form>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection