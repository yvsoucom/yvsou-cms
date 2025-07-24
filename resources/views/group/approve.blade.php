{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-07-09
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
  <div class="overflow-x-auto">
    <form method="POST" action="{{ route('group.storeapprove') }}">
    @csrf
    <input type="hidden" name="groupid" value="{{$groupid}}">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-800">
      <tr>
        <th class="px-4 py-2">
        <input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500 dark:bg-gray-700 dark:border-gray-600">
        </th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        {{ __('domain.User ID') }} 
        </th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        {{ __('domain.Name') }} 
        </th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        {{ __('domain.Email') }} 
        </th>
      </tr>
      </thead>
      <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

      @foreach ($users as $user)
      <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
      <td class="px-4 py-2">
      <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
        class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500 dark:bg-gray-700 dark:border-gray-600">
      </td>
      <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200">{{ $user->id }}</td>
      <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200">{{ $user->name }}</td>
      <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
      </tr>
    @endforeach

      </tbody>
    </table>

    <div class="mt-4">
      <button type="submit" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded hover:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors">
      {{ __('domain.Submit Selected') }} 
      </button>
    </div>
    </form>
  </div>

@endsection

<script>
  // Select/Deselect all checkboxes
  document.getElementById('select-all').addEventListener('click', function (event) {
    const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
    checkboxes.forEach(cb => cb.checked = event.target.checked);
  });
</script> 