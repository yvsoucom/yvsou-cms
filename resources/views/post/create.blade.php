{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-26
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

  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-3xl bg-white p-6 rounded-2xl shadow-lg">
    <h1 class="text-center text-2xl md:text-3xl font-bold text-gray-800 mb-6">
     {{ __('post.Create New Post') }}
    </h1>

    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf
      <input type="hidden" name="groupid" value="{{ $groupid }}">

      <!-- Title Input -->
      <div>
      <label for="title" class="block text-sm font-medium text-gray-700">{{ __('post.posttitle') }} </label>
      <input type="text" id="title" name="title"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        placeholder="Enter post title" required>
      </div>

      <!-- Content Editor -->
      <div>
      <label for="ys_editor" class="block text-sm font-medium text-gray-700">{{ __('post.postcontent') }} </label>
      <textarea id="ys_editor" name="content"
        class="mt-1 block w-full h-48 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        placeholder="Write your content here..." required></textarea>
      </div>
      <!-- Include the file library modal near the editor -->
      <div id="fileLibraryModal"
      style="display:none; position:fixed; top:10%; left:10%; width:80%; height:80%; overflow:auto; background:white; border:1px solid #ccc; padding:10px; z-index:10000;">
      <button onclick="closeLibraryModal()" style="float:right;">{{ __('post.close') }} </button>
      <h3>{{ __('post.selectfilefromlib') }} </h3>
      <div id="fileLibraryList"></div>
      </div>
      <!-- Submit Button -->
      <div class="flex justify-end">
      <button type="submit"
        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
    window.trumbowygSvgPath = "{{asset('build/icons/trumbowyg/icons.svg') }}";
    window.shouldLoadEditor = true;
  </script>
  @vite('resources/js/editor.js')
@endpush