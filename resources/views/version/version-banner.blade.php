<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025  
// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
{{-- resources/views/version/version-banner.blade.php --}}
@inject('versionCheck', 'App\Services\AutoUpdaterService')

@php
    $result = $versionCheck->isOutdated();  
@endphp
<div class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 p-4 mb-4 rounded-lg transition-colors duration-200">
    <strong>Current Version:</strong> {{ $result['current'] }}

    @if ($result['outdated'])
        <br>
        <strong class="text-yellow-800 dark:text-yellow-400">New Version Available!</strong><br>
        Latest version: <strong>{{ $result['latest'] }}</strong> —
        
        <form method="POST" action="{{ route('admin.updater.run') }}" class="mt-2">
            @csrf
            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded transition-colors duration-200">
                Update Now
            </button>
        </form>
    @else
        <br>
        <span class="text-green-600 dark:text-green-400">✅ You are up-to-date.</span>
    @endif
</div> 