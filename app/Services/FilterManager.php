<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 * SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
 * SPDX-FileContributor: Lican Huang
 * @created 2025-08-13
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

namespace App\Services;


use InvalidArgumentException;

class FilterManager
{
    protected static array $filters = [];

    // Allowed namespace prefix for class method callbacks, e.g. 'App\Filters\'
    protected static string $allowedNamespace = 'App\\Filters\\';

    /**
     * Register a filter callback for a tag.
     *
     * @param string   $tag
     * @param callable $callback Must be Closure, global function, or class method within allowed namespace.
     * @param int      $priority Lower numbers run earlier.
     *
     * @throws InvalidArgumentException if callback is invalid.
     */
    public static function addFilter(string $tag, callable $callback, int $priority = 10, int $accepted_args = 1): void
    {
        // Validate callback
        if (is_array($callback)) {
            // Class method callback
            $class = is_object($callback[0]) ? get_class($callback[0]) : $callback[0];
            $method = $callback[1] ?? null;

            if (!$method || !method_exists($callback[0], $method)) {
                throw new InvalidArgumentException("Invalid callback: method '{$method}' does not exist in class '{$class}'.");
            }

            if (strpos($class, self::$allowedNamespace) !== 0) {
                throw new InvalidArgumentException("Class method callback must be inside namespace '" . self::$allowedNamespace . "'. Got '{$class}'.");
            }
        } elseif (is_string($callback)) {
            // Global function callback
            if (!function_exists($callback)) {
                throw new InvalidArgumentException("Function callback '{$callback}' does not exist.");
            }
        } elseif ($callback instanceof \Closure) {
            // Closure is always allowed
        } else {
            throw new InvalidArgumentException('Invalid filter callback. Must be Closure, global function, or class method.');
        }

        // Store callback along with accepted args
        self::$filters[$tag][$priority][] = [
            'callback' => $callback,
            'accepted_args' => $accepted_args
        ];
    }

    public static function applyFilters(string $tag, $value, ...$args)
    {
        if (!isset(self::$filters[$tag])) {
            return $value;
        }

        ksort(self::$filters[$tag]);

        foreach (self::$filters[$tag] as $priority => $callbacks) {
            foreach ($callbacks as $item) {
                $callback = $item['callback'];
                $accepted = array_slice(array_merge([$value], $args), 0, $item['accepted_args']);
                $value = call_user_func_array($callback, $accepted);
            }
        }

        return $value;
    }

}
