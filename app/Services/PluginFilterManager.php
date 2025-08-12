<?php
 
// app/Services/PluginFilterManager.php
namespace App\Services;

class PluginFilterManager
{
    protected array $managers = [];

    public function getManager(string $pluginName): FilterManager
    {
        if (!isset($this->managers[$pluginName])) {
            $this->managers[$pluginName] = new FilterManager();
        }
        return $this->managers[$pluginName];
    }
}
