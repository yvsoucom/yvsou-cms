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

@extends('layouts.app')
@section('content')
    <div class="max-w-md mx-auto p-6">
        <form id="copypostlink" method="POST" action="{{ route('post.copygroupupdate', compact('groupid', 'pid')) }}"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors duration-200">
            @csrf
            @method('PATCH')
            
            <div class="space-y-4">
                <div>
                    <label for="editor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('post.target_groupid') }}
                    </label>
                    <input type="text" name="desgroupid" id="editor"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2"
                           value="" maxlength="350">
                    
                    <input type="hidden" name="groupid" value="{{ $groupid }}">
                    <input type="hidden" name="pid" value="{{ $pid }}">
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2 text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        {{ __('post.copy_targetid') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection 