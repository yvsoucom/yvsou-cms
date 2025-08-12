<?php

namespace App\Theme;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;


class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/theme.php', 'theme');


    }

    public function boot()
    {
        $activeTheme = config('theme.active');
        $themePath = base_path("plugins/{$activeTheme}");
        $themeViewPath = base_path("plugins/{$activeTheme}/views");

        $finder = View::getFinder();

        if ($finder instanceof FileViewFinder) {
            $finder->prependLocation($themeViewPath);
        } else {
            $finder->addLocation($themeViewPath);
        }




        $functions = $themePath . '/functions.php';
        if (file_exists($functions)) {
            require_once $functions;
        }

        $assetsPath = $themePath . '/assets';
        if (is_dir($assetsPath)) {
            $this->publishes([
                $assetsPath => public_path('themes/' . $activeTheme),
            ], 'themes-assets');
        }
    }
}

