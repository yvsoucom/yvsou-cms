{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-07-09
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
    <div class="container">
        <h1>{{__('right.editdomainright')}}{{ $groupid }}</h1>

        @if (session('success'))
            <div class="bg-green-200 p-2 my-2">{{ session('success') }}</div>
        @endif



        <table class="table-auto border-collapse w-full">
            <thead>

                <tr>
                    <th class="text-left px-4 py-2">{{__('right.Role')}}</th>
                    <th class="text-left px-4 py-2">{{__('right.writedir')}}</th>
                    <th class="text-left px-4 py-2">{{__('right.readdir')}}</th>
                    <th class="text-left px-4 py-2">{{__('right.adddir')}}</th>
                    <th class="text-left px-4 py-2">{{__('right.showdir')}}</th>
                    <th class="text-left px-4 py-2">{{__('right.action')}}</th>
                </tr>

            </thead>
            <tbody>

                @foreach ($rights as $role)
                    <tr class="border-t">
                        <form method="POST" action="{{ route('domainview.rights.update', $groupid) }}">
                            @csrf
                            <input type="hidden" name="groupid" value="{{ $groupid }}">
                            <input type="hidden" name="role_key" value="{{ $role['key'] }}">

                            <td class="p-2">{{__('right.'.$role['label'])}}</td>

                            @php

                                $value = $role['value'];

                            @endphp

                            <td class="p-2">
                                <input type="radio" name="write" value="1" {{ ($value >> 3) & 1 ? 'checked' : '' }}>Yes
                                <input type="radio" name="write" value="0" {{ ((($value >> 3) & 1) == 0) ? 'checked' : '' }}>No
                            </td>
                            <td class="p-2">
                                <input type="radio" name="read" value="1" {{ ($value >> 2) & 1 ? 'checked' : '' }}>Yes
                                <input type="radio" name="read" value="0" {{ ((($value >> 2) & 1) == 0) ? 'checked' : '' }}>No
                            </td>
                            <td class="p-2">
                                <input type="radio" name="addchild" value="1" {{ ($value >> 1) & 1 ? 'checked' : '' }}>Yes
                                <input type="radio" name="addchild" value="0" {{ ((($value >> 1) & 1) == 0) ? 'checked' : '' }}>No
                            </td>
                            <td class="p-2">
                                <input type="radio" name="showdir" value="1" {{ ($value >> 0) & 1 ? 'checked' : '' }}>Yes
                                <input type="radio" name="showdir" value="0" {{ ((($value >> 0) & 1) == 0) ? 'checked' : '' }}>No
                            </td>
                            <td class="p-2">
                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">{{__('right.save')}}</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection