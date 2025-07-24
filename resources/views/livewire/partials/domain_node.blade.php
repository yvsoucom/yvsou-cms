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
 