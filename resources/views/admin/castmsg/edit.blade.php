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
    <form action="{{ route('admin.castmsg.update') }}" method="POST"
        class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow space-y-4">
        @csrf
    <!-- #region -->      <h2 class="text-2xl font-bold text-gray-800 mb-4">💬 {{__('message.newplatformcast')}}</h2>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
               
            </label>
            <textarea id="message" name="message" rows="5"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                placeholder="Type your message here...">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">

            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                {{__('message.savemessage')}}
            </button>
        </div>
    </form>

@endsection