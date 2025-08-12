<?php
 

// app/Helpers/FilterManager.php

namespace App\Helpers;

class FilterManager
{
    protected static $filters = [];

    public static function addFilter(string $tag, callable $callback, int $priority = 10)
    {
        self::$filters[$tag][$priority][] = $callback;
    }

    public static function applyFilters(string $tag, $value, ...$args)
    {
        if (!isset(self::$filters[$tag])) {
            return $value;
        }

        ksort(self::$filters[$tag]);

        foreach (self::$filters[$tag] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $value = call_user_func($callback, $value, ...$args);
            }
        }

        return $value;
    }
}
