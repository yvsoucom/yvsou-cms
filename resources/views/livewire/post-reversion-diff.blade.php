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
 
<div class="container mx-auto p-6 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-900">
    <h1 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('livewire.reversiondiff') }}</h1>

    <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto prose max-w-none dark:prose-invert">
        {!! $diffHtml !!}
    </div>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('livewire.Back') }}</a>
    </div>
</div>