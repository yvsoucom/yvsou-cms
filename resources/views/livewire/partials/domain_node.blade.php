{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-14
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
<li class="mb-1">
    @php
        $groupid = $domain['groupid'];    
        $name = $domain['name'];    
        $hasChildren = !empty($children[$groupid] ?? []);        
    @endphp

    <div class="flex items-center space-x-2">
        @if ($hasChildren)
            <button
                wire:click="toggle('{{ $groupid }}')"
                class="w-6 h-6 flex items-center justify-center rounded border border-gray-400 text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring focus:ring-indigo-300"
                aria-label="{{ in_array($groupid, $expanded) ? 'Collapse' : 'Expand' }} domain {{ $name }}"
            >
                {{ in_array($groupid, $expanded) ? '-' : '+' }}
            </button>
        @else
            <span class="w-6 h-6"></span>
        
        @endif

        <span class="text-gray-800 break-words">{!! $name !!}</span>
    </div>

    @if (in_array($groupid, $expanded) && $hasChildren)
        <ul class="ml-6 border-l border-gray-300 mt-1 pl-4">
            @foreach ($children[$groupid] as $childGroupid)
                @php
                    $childName = (new \App\Services\DomainService())->get_joinLink_by_uniqid($childGroupid);
                @endphp
                @include('livewire.partials.domain_node', [
                    'domain' => ['groupid' => $childGroupid, 'name' => $childName],
                    'children' => $children,
                    'expanded' => $expanded,
                ])
            @endforeach
        </ul>
    @endif
</li>
 