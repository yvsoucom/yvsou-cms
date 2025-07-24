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
    <form action="{{ route('group.messagestore') }}" method="POST"
        class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg space-y-4 transition-colors duration-200">
        @csrf
        <input type="hidden" name="groupid" value="{{$groupid}}">
        @foreach ($userids as $userid)
            <input type="hidden" name="userids[]" value="{{ $userid }}">
        @endforeach

        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">💬 {{ __('message.New Message to users') }}</h2>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('message.Message') }}
            </label>
            <textarea id="message" name="message" rows="5"
                class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500/30 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-colors duration-200"
                placeholder="Type your message here...">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                {{ __('message.Send Message') }}
            </button>
        </div>
    </form>
@endsection 