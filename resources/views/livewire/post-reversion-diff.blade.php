{{--
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-26
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
 
<div class="container mx-auto p-6 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-900">
    <h1 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('livewire.reversiondiff') }}</h1>

    <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto prose max-w-none dark:prose-invert">
        {!! $diffHtml !!}
    </div>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('livewire.Back') }}</a>
    </div>
</div>