{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-07-09
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
    <form action="{{ route('group.castmessagestore') }}" method="POST"
        class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-4">
        @csrf
        <input type="hidden" name="groupid" value="{{$groupid}}">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">💬 {{ __('message.New group broadcast Message') }}</h2>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('message.Message') }}
            </label>
            <textarea id="message" name="message" rows="5"
                class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/20 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                placeholder="Type your message here...">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded hover:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors">
                {{ __('message.Send Message') }}
            </button>
        </div>
    </form>
@endsection 