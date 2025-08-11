<?php

namespace App\Theme;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/theme.php',
            'theme'
        );

        // Bind the ThemeManager as a singleton
        $this->app->singleton(ThemeManager::class, function ($app) {
            return new ThemeManager();
        });

        // Register console commands
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
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/theme.php' => config_path('theme.php'),
        ], 'config');

        $themePath = $themes->path();
        $viewsPath = $themePath . '/views';

        // Add theme views, overriding defaults if possible
        if (is_dir($viewsPath)) {
            $finder = View::getFinder();
            if (method_exists($finder, 'prependLocation')) {
                // Modern Laravel — override priority
                $finder->prependLocation($viewsPath);
            } else {
                // Fallback for older Laravel
                $finder->addLocation($viewsPath);
            }
        }

        // Auto-load theme-specific functions.php if it exists
        $functions = $themePath . '/functions.php';
        if (file_exists($functions)) {
            require_once $functions;
        }

        // Publish theme assets to public/themes/{themeName}
        $this->publishes([
            $themePath . '/assets' => public_path('themes/' . $themes->active()),
        ], 'themes-assets');
    }
}
 