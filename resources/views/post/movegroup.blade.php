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
    <div class="max-w-xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <form id="movepostlink" method="POST" action="{{ route('post.movegroupupdate', compact('groupid', 'pid')) }}"
            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-6 transition-colors duration-200">
            @csrf
            @method('PATCH')

            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ __('post.move_targetid') }}</h2>

            <div>
                <label for="desgroupid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('post.target_groupid') }}
                </label>
                <input type="text" id="desgroupid" name="desgroupid"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                    maxlength="350">
            </div>

            <input type="hidden" name="groupid" value="{{ $groupid }}">
            <input type="hidden" name="pid" value="{{ $pid }}">

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                    {{ __('post.move_targetid') }}
                </button>
            </div>
        </form>
    </div>
@endsection 