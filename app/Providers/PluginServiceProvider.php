<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function register()
    {
        $pluginPath = base_path('plugins');

        foreach (glob("$pluginPath/*/src/*ServiceProvider.php") as $providerPath) {
            require_once $providerPath;

            $class = $this->getClassFromPath($providerPath);

            if (class_exists($class)) {
                $this->app->register($class);
            } else {
                logger()->warning("Plugin class [$class] not found in [$providerPath]");
            }

            // 🔽 Load translations dynamically
            $pluginRoot = dirname(dirname($providerPath)); // e.g., plugins/MoneyPlugin
            $langPath = $pluginRoot . '/resources/lang';
            $pluginName = basename($pluginRoot);

            if (is_dir($langPath)) {
                $this->loadTranslationsFrom($langPath, $pluginName);
            }
        }
    }


    private function getClassFromPath(string $path): string
    {
        // Remove base path and normalize slashes
        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path);

        // Convert path to namespace
        $class = str_replace(['/', '\\'], '\\', $relativePath);
        return str_replace('.php', '', $class); // Final result
    }
}
