<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2026-01-06
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


namespace App\Http\Controllers\Install;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class WebSocketController extends Controller
{
    public function show()
    {
        return view('install.websocket');
    }

    public function store(Request $request)
    {
        $request->validate([
            'driver' => 'required|in:workerman,swoole',
        ]);

        $driver = $request->input('driver');

        if ($driver === 'swoole' && !extension_loaded('swoole')) {
            return back()->withErrors(['driver' => 'Swoole extension is not installed.']);
        }

        // Update .env
        $this->setEnv('WEBSOCKET_DRIVER', $driver);

        return redirect()->route('install.next.step')
            ->with('success', "WebSocket driver set to {$driver}");
    }

    protected function setEnv($key, $value)
    {
        $path = base_path('.env');
        if (!File::exists($path)) return;

        $env = File::get($path);

        if (preg_match("/^{$key}=.*$/m", $env)) {
            $env = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $env);
        } else {
            $env .= "\n{$key}={$value}\n";
        }

        File::put($path, $env);
    }
}
