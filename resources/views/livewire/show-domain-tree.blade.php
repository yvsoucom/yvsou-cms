<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
 
<div class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 p-4 rounded">
    @if ($domain)
        <ul>
            @include('livewire.partials.domain_node', [
                'domain' => $domain,
                'children' => $children,
                'expanded' => $expanded,
            ])
        </ul>
    @else
        <p>{{ __('livewire.norootdomain') }}</p>
    @endif
</div>
