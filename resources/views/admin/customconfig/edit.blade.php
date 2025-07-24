{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-29
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

    <h1 class="text-2xl font-bold mb-4">{{__('setpages.editpages')}} </h1>

    @if(session('success'))
        <div class="p-2 bg-green-200 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.setcustomconfig.update') }}">
        @csrf


        <label>{{ __('setpages.about') }}</label>
        <textarea name="about" rows="5" class="w-full mb-4">{{ $pages['about'] ?? '' }}</textarea>

        <label>{{ __('setpages.contact') }} </label>
        <textarea name="contact" rows="5" class="w-full mb-4">{{ $pages['contact'] ?? '' }}</textarea>

        <label>{{ __('setpages.terms') }} </label>
        <textarea name="terms" rows="5" class="w-full mb-4">{{ $pages['terms'] ?? '' }}</textarea>

        <label>{{ __('setpages.privacy') }} </label>
        <textarea name="privacy" rows="5" class="w-full mb-4">{{ $pages['privacy'] ?? '' }}</textarea>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('setpages.save') }} </button>
    </form>


@endsection