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