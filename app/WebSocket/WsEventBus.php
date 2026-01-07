<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2026-01-07
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

namespace App\WebSocket;

class WsEventBus
{
    /**
     * Array of registered listeners
     * Format: ['event.name' => [callable1, callable2, ...]]
     */
    protected static array $listeners = [];

    /**
     * Register a listener for an event
     *
     * @param string   $event
     * @param callable $callback function(...$args)
     */
    public static function on(string $event, callable $callback): void
    {
        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }
        self::$listeners[$event][] = $callback;
    }

    /**
     * Dispatch an event
     *
     * @param string $event
     * @param mixed ...$args
     */
    public static function dispatch(string $event, ...$args): void
    {
        if (!isset(self::$listeners[$event])) return;

        foreach (self::$listeners[$event] as $listener) {
            try {
                $listener(...$args);
            } catch (\Throwable $e) {
                error_log("WsEventBus listener error for event '{$event}': " . $e->getMessage());
            }
        }
    }
}
