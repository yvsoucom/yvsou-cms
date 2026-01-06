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
 

@extends('layouts.install')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Step 2: WebSocket Server</h2>

    <form method="POST" action="{{ route('install.websocket.store') }}">
        @csrf

        <p class="mb-4">Choose your WebSocket server driver. You can only select one.</p>

        <div class="space-y-3 mb-6">
            <label class="flex items-center space-x-3">
                <input type="radio" name="driver" value="workerman" checked>
                <span>Workerman (no PHP extension required)</span>
            </label>

            <label class="flex items-center space-x-3">
                <input type="radio" name="driver" value="swoole" {{ extension_loaded('swoole') ? '' : 'disabled' }}>
                <span>Swoole (requires Swoole PHP extension)</span>
            </label>

            @if(!extension_loaded('swoole'))
            <p class="text-sm text-red-500">Swoole extension not detected. Please install Swoole to enable this option.</p>
            @endif
        </div>

        <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Next
        </button>
    </form>
</div>
@endsection
