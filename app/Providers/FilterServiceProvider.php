<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use App\Services\FilterManager;
use App\Services\PluginFilterManager;
class FilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register FilterManager in Laravel's container
        $this->app->singleton(FilterManager::class, function () {
            return new FilterManager();
        });

        // Optional alias so we can call `app('filter')`
        $this->app->alias(FilterManager::class, 'filter');
        
        $this->app->singleton(PluginFilterManager::class, function ($app): PluginFilterManager {
            return new PluginFilterManager();
        });

    }
    protected function loadFiltersFrom(string $dir, ?FilterManager $manager = null): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                // Only require the filter file if $manager is provided or just for app filters
                require $file->getRealPath();
            }
        }
    }

    public function boot(): void
    {
        // Load app global filters
        $this->loadFiltersFrom(app_path('Filters'));

        $pluginBasePath = base_path('plugins');
        if (is_dir($pluginBasePath)) {
            foreach (scandir($pluginBasePath) as $pluginName) {
                if ($pluginName === '.' || $pluginName === '..') {
                    continue;
                }
                $pluginPath = $pluginBasePath . DIRECTORY_SEPARATOR . $pluginName;
                $enabledFlagFile = $pluginPath . DIRECTORY_SEPARATOR . 'enabled.flag';

                if (is_dir($pluginPath) && file_exists($enabledFlagFile)) {
                    // Load plugin filters only if enabled.flag exists
                    $filterDir = $pluginPath . DIRECTORY_SEPARATOR . 'Filters';
                    if (is_dir($filterDir)) {
                        // Pass plugin's FilterManager instance if needed
                        $pluginFilterManager = app(PluginFilterManager::class)->getManager($pluginName);
                        $this->loadFiltersFrom($filterDir, $pluginFilterManager);
                    }
                }
            }
        }
    }
}
