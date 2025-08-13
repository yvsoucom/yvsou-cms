<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShortcodeManager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use App\Services\PluginShortcodeManager;

class ShortcodeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ShortcodeManager::class, function ($app) {
            return new ShortcodeManager();
        });

        $this->app->singleton(PluginShortcodeManager::class, function ($app) {
        return new PluginShortcodeManager();
    });
    }
    public function boot(): void
    {
        // Load global app shortcodes
        $this->loadShortcodesFrom(app_path('Shortcodes'));

        $pluginBasePath = base_path('plugins');
        if (is_dir($pluginBasePath)) {
            foreach (scandir($pluginBasePath) as $pluginName) {
                if ($pluginName === '.' || $pluginName === '..') {
                    continue;
                }
                $pluginPath = $pluginBasePath . DIRECTORY_SEPARATOR . $pluginName;
                $enabledFlagFile = $pluginPath . DIRECTORY_SEPARATOR . 'enabled.flag';

                if (is_dir($pluginPath) && file_exists($enabledFlagFile)) {
                    $shortcodeDir = $pluginPath . DIRECTORY_SEPARATOR . 'Shortcodes';
                    if (is_dir($shortcodeDir)) {
                        $pluginShortcodeManager = app(PluginShortcodeManager::class)->getManager($pluginName);
                        $this->loadShortcodesFrom($shortcodeDir, $pluginShortcodeManager);
                    }
                }
            }
        }
    }

    protected function loadShortcodesFrom(string $dir, ?ShortcodeManager $manager = null): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                // You can include shortcode registration files here.
                require $file->getRealPath();
            }
        }
    }
}