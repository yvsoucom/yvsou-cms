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
  <main class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900">

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">

    <form method="POST" action="{{ route('domainview.updatedomain') }}" class="space-y-6">
      @csrf
      <input type="hidden" name="groupid" value="{{$groupid}}">
      <input type="hidden" name="id" value="{{$domainproperty->first()->id}}">
      <input type="hidden" name="lang" value="{{$domainproperty->first()->lang}}">

      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
      {{ __('domain.updatedomaintitledescription') }} </h2>


      <div class="grid gap-6 md:grid-cols-2">
      <div class="space-y-3">
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ __('domain.domaintitle') }}
        </label>
        <input type="text" id="title" name="title"
        placeholder="{{ $domainproperty->first()->domain_dict_name ?? '' }}"
        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 p-2 focus:ring focus:ring-blue-300 focus:outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200">

        <label for="desc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ __('domain.domaindescription') }}
        </label>
        <textarea id="description" name="description" placeholder="{{ $domainproperty->first()->description ?? '' }}"
        rows="3"
        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 p-2 focus:ring focus:ring-blue-300 focus:outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"></textarea>
      </div>

      </div>

      <div class="pt-4">
      <button type="submit"
        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
        {{ __('domain.submit') }}
      </button>
      </div>
    </form>
    </div>
  </main>
@endsection