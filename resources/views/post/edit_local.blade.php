{{--
 SPDX-FileCopyrightText:  (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 SPDX-FileCopyrightText:   
 SPDX-FileContributor: Lican Huang
 @created 2025-09-05
*
* SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
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
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4 py-8">
  <div class="w-full max-w-3xl bg-white dark:bg-gray-400 p-6 rounded-2xl shadow-lg transition-colors duration-200">
    <h1 class="text-center text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-800 mb-6">
      {{ __('post.Edit Post (Local File Reference)') }}
    </h1>

    <form method="POST" action="{{ route('post.update.local', $post->id) }}" class="space-y-5">
      @csrf
      @method('PUT')
      <input type="hidden" name="groupid" value="{{ $post->groupid }}">

      <!-- Title Input -->
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
          {{ __('post.posttitle') }}
        </label>
        <input type="text" id="title" name="title"
          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-400 shadow-sm
                 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                 bg-white dark:bg-gray-100 text-gray-900 dark:text-gray-800 placeholder-gray-400"
          value="{{ old('title', $post->title) }}" required>
      </div>

      <!-- Content Editor -->
      <div>
        <label for="ys_editor" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
          {{ __('post.postcontent') }}
        </label>
        <textarea id="ys_editor" name="content"
          class="mt-1 block w-full h-48 rounded-md border-gray-300 dark:border-gray-400 shadow-sm
                 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                 bg-white dark:bg-gray-100 text-gray-900 dark:text-gray-800 placeholder-gray-400"
          required>{{ old('content', $post->content) }}</textarea>
      </div>

      <!-- Local File Reference -->
      <div>
        <label for="local_file" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
          {{ __('post.localfile') }}
        </label>
        <div class="flex gap-2 mt-1">
          <input type="text" id="local_file" name="local_file"
            placeholder="e.g. C:\Shared\doc.pdf or \\NAS\share\doc.pdf"
            value="{{ old('local_file', $post->local_file) }}"
            class="flex-1 block text-sm text-gray-700 dark:text-gray-700 border border-gray-300 rounded-md p-2
                   bg-white dark:bg-gray-100 focus:ring-blue-500 focus:border-blue-500">
          <button type="button" onclick="openLibraryModal()"
                  class="px-3 py-1 bg-gray-200 dark:bg-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-400 transition-colors">
            {{ __('post.selectfromlib') }}
          </button>
        </div>
      </div>

      <!-- File Library Modal -->
      <div id="fileLibraryModal"
           class="hidden fixed top-[10%] left-[10%] w-4/5 h-4/5 overflow-auto bg-white dark:bg-gray-200 border border-gray-300 dark:border-gray-400 p-4 z-[10000] rounded-lg shadow-xl">
        <button type="button" onclick="closeLibraryModal()"
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
        <a href="{{ route('domainview.index', ['groupid' => $post->groupid]) }}"
           class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm
                  text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2
                  focus:ring-offset-2 focus:ring-blue-500">
          {{ __('post.Cancel') }}
        </a>
        <button type="submit"
                class="ml-3 inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md
                       shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none
                       focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-200 transition-colors">
          {{ __('post.update') }}
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
  window.trumbowygSvgPath = "{{ asset('build/icons/vendor/trumbowyg/icons.svg') }}";
  window.shouldLoadEditor = true;

  function openLibraryModal() {
    document.getElementById('fileLibraryModal').classList.remove('hidden');
    loadFileLibrary();
  }

  function closeLibraryModal() {
    document.getElementById('fileLibraryModal').classList.add('hidden');
  }

  function loadFileLibrary() {
    const list = document.getElementById('fileLibraryList');
    list.innerHTML = '';
    const files = ['\\NAS\\share\\doc1.pdf', '\\NAS\\share\\doc2.pdf']; // TODO: dynamic via AJAX
    files.forEach(file => {
      const div = document.createElement('div');
      div.className = 'p-2 border-b border-gray-300 dark:border-gray-400 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-300';
      div.textContent = file;
      div.onclick = () => {
        document.getElementById('local_file').value = file;
        closeLibraryModal();
      };
      list.appendChild(div);
    });
  }
</script>
@vite('resources/js/editor.js')
@endpush
