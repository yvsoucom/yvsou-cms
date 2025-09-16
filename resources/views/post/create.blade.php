<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025  
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
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-3xl bg-white dark:bg-gray-400 p-6 rounded-2xl shadow-lg transition-colors duration-200">
    <h1 class="text-center text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-800 mb-6">
      {{ __('post.Create New Post') }}
    </h1>

    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf
      <input type="hidden" name="groupid" value="{{ $groupid }}">

      <!-- Title Input -->
      <div>
      <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
        {{ __('post.posttitle') }}
      </label>
      <input type="text" id="title" name="title"
        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-400 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-100 text-gray-900 dark:text-gray-800 placeholder-gray-400 dark:placeholder-gray-500"
        placeholder="Enter post title" required>
      </div>

      <!-- Content Editor -->
      <div>
      <label for="ys_editor" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
        {{ __('post.postcontent') }}
      </label>
      <textarea id="ys_editor" name="content"
        class="mt-1 block w-full h-48 rounded-md border-gray-300 dark:border-gray-400 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-100 text-gray-900 dark:text-gray-800 placeholder-gray-400 dark:placeholder-gray-500"
        placeholder="Write your content here..." required></textarea>
      </div>

      <!-- File Library Modal -->
      <div id="fileLibraryModal"
      class="hidden fixed top-[10%] left-[10%] w-4/5 h-4/5 overflow-auto bg-white dark:bg-gray-200 border border-gray-300 dark:border-gray-400 p-4 z-[10000] rounded-lg shadow-xl">
      <button onclick="closeLibraryModal()"
        class="float-right px-3 py-1 bg-gray-200 dark:bg-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-400 transition-colors">
        {{ __('post.close') }}
      </button>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-800 mt-2">
        {{ __('post.selectfilefromlib') }}
      </h3>
      <div id="fileLibraryList" class="mt-4"></div>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end">
      <a href="{{ route('domainview.index', compact('groupid')) }}" class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm
      text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        {{ __('post.Cancel') }}
      </a>
      <button type="submit"
        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-200 transition-colors">
        {{ __('post.publish') }}
      </button>
      </div>
    </form>
    </div>
  </div>
@endsection

@push('styles')
  <script>window.shouldLoadEditor = true;</script>
  @vite('resources/css/editor.css')
@endpush
@push('scripts')
  <script>
    window.trumbowygSvgPath = "{{asset('build/icons/vendor/trumbowyg/icons.svg') }}";
    window.shouldLoadEditor = true;
  </script>
  @vite('resources/js/editor.js')
@endpush