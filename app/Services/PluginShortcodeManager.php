<?php
 

namespace App\Services;
class PluginShortcodeManager
{
    /**
     * @var ShortcodeManager[]
     */
    protected array $managers = [];

    /**
     * Get or create a ShortcodeManager instance for a plugin by name
     */
    public function getManager(string $pluginName): ShortcodeManager
    {
        if (!isset($this->managers[$pluginName])) {
            $this->managers[$pluginName] = new ShortcodeManager();
        }
        return $this->managers[$pluginName];
    }
}