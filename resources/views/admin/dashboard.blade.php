{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-15
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
    <div class="absolute right-0 top-0 w-64 p-4">
        <h2 class="text-xl font-bold mb-4">Dashboard</h2>
        <nav class="space-y-3">
            <a href="/admin/dashboard" class="block text-gray-800 hover:font-semibold">Dashboard</a>
            <a href="/admin/plugins" class="block text-gray-800 hover:font-semibold">Plugins</a>
            <a href="/admin/users" class="block text-gray-800 hover:font-semibold">Users</a>
        </nav>
    </div>

@endsection