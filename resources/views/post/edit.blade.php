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
 
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-3xl bg-white dark:bg-gray-400 p-6 rounded-2xl shadow-lg transition-colors duration-200">
    <h1 class="text-center text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-800 mb-6">
      {{ __('post.Edit Post') }}
    </h1>
    <form method="POST" action="{{ route('post.update') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf
      <input type="hidden" name="groupid" value="{{ $groupid }}">
      <input type="hidden" name="postid" value="{{ $post->id }}">

      <!-- Title Input -->
      <div>
      <label for="title" class="block text-sm font-medium text-gray-700">{{ __('post.posttitle') }}</label>
      <input type="text" id="title" name="title"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        value="{{ old('title', $post->post_title) }}" required>
      </div>

      <!-- Content Editor -->
      <div>
      <label for="ys_editor" class="block text-sm font-medium text-gray-700">{{ __('post.postcontent') }}</label>
      <textarea id="ys_editor" name="content"
        class="mt-1 block w-full h-48 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        required> {{ old('content', $post_content) }}  </textarea>
      </div>
      <!-- Include the file library modal near the editor -->
      <div id="fileLibraryModal"
      style="display:none; position:fixed; top:10%; left:10%; width:80%; height:80%; overflow:auto; background:white; border:1px solid #ccc; padding:10px; z-index:10000;">
      <button onclick="closeLibraryModal()" style="float:right;">{{ __('post.close') }}</button>
      <h3>{{ __('post.selectfilefromlib') }}</h3>
      <div id="fileLibraryList"></div>
      </div>
      <!-- Submit Button -->
      <div class="flex justify-end">
      <button type="submit"
        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        {{ __('post.updatepost') }}
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
    window.shouldLoadEditor = true;
    window.trumbowygSvgPath = "{{asset('build/icons/trumbowyg/icons.svg') }}";

  </script>
  @vite('resources/js/editor.js')
@endpush