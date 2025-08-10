<?php

namespace App\Theme;

use Illuminate\Support\Facades\File;

class ThemeManager
{
    protected string $themePath;

    public function __construct()
    {
        $this->themePath = resource_path('themes');
    }

    public function all(): array
    {
        if (! File::exists($this->themePath)) {
            return [];
        }
        $themes = [];
        foreach (File::directories($this->themePath) as $dir) {
            $metaFile = $dir . '/theme.json';
            if (File::exists($metaFile)) {
                $meta = json_decode(File::get($metaFile), true) ?: [];
                $slug = basename($dir);
                $themes[$slug] = array_merge(['slug' => $slug], $meta);
            }
        }
        return $themes;
    }

    public function active(): string
    {
        return config('theme.active', 'default');
    }

    public function path(?string $slug = null): string
    {
        $slug = $slug ?: $this->active();
        return $this->themePath . '/' . $slug;
    }

    public function exists(string $slug): bool
    {
        return is_dir($this->themePath . '/' . $slug) && File::exists($this->themePath . '/' . $slug . '/theme.json');
    }
}
