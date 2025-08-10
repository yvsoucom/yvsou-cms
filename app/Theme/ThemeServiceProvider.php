<?php

namespace App\Theme;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/theme.php', 'theme');

        $this->app->singleton(ThemeManager::class, function ($app) {
            return new ThemeManager();
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Theme\ThemeListCommand::class,
                \App\Theme\ThemeSetCommand::class,
                \App\Theme\ThemePublishAssetsCommand::class,
            ]);
        }
    }

    public function boot(ThemeManager $themes)
    {
        $this->publishes([
            __DIR__ . '/../../config/theme.php' => config_path('theme.php'),
        ], 'config');

        $themePath = $themes->path();
        $viewsPath = $themePath . '/views';

        if (is_dir($viewsPath)) {
          //  View::getFinder()->prependLocation($viewsPath);
            View::getFinder()->addLocation($viewsPath);

        }

        $functions = $themePath . '/functions.php';
        if (file_exists($functions)) {
            require_once $functions;
        }

        $this->publishes([
            $themePath . '/assets' => public_path('themes/' . $themes->active()),
        ], 'themes-assets');
    }
}
