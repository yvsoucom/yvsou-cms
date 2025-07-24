{{--
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-26
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