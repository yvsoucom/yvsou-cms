<?php
// plugins/MyPlugin/Shortcodes/PluginExampleShortcode.php
use function App\Helpers\add_plugin_shortcode;
 

$pluginName = 'DefaultTheme';

// Register shortcode [myplugin_hello] scoped to MyPlugin
add_plugin_shortcode($pluginName, 'hello', function ($attrs) {
    $name = $attrs['name'] ?? 'Visitor';
    return "Welcome, {$name}, from MyPlugin!";
});
