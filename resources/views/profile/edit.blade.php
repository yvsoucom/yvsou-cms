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
<!-- #region -->
{{-- resources/views/profile/edit.blade.php --}}

@extends('layouts.app')
 
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('profile.title') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Profile Info Form --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Password Form --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User Form --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-200">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection