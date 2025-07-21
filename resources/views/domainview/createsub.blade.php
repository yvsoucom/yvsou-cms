{{--
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
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
    <main class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
            <form method="POST" action="{{ route('domainview.storesub') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="groupid" value="{{$groupid}}">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __( 'domain.createdomaintitledescription') }}</h2>

                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($getlangSet as $code => $language)
                        <div class="space-y-3">
                            <label for="title-{{ $code }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $language }} {{ __( 'domain.domaintitle') }}
                            </label>
                            <input type="text" id="title-{{ $code }}" name="titles[{{ $code }}]"
                                placeholder="Title in {{ $language }}"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 p-2 focus:ring focus:ring-blue-300 focus:outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200">

                            <label for="desc-{{ $code }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $language }} {{ __( 'domain.domaindescription') }}
                            </label>
                            <textarea id="desc-{{ $code }}" name="descriptions[{{ $code }}]"
                                placeholder="Description in {{ $language }}" rows="3"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 p-2 focus:ring focus:ring-blue-300 focus:outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"></textarea>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                        {{ __( 'domain.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
 