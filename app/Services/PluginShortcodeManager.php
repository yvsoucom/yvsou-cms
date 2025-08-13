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

    public function render(string $content): string
    {
        logger('debug plugin managers', $this->managers);

        if (empty($this->managers)) {
            return $content;
        }

        $pluginNames = array_map('preg_quote', array_keys($this->managers));
        logger('debug plugin pluginNames', $pluginNames);

        if (empty($pluginNames)) {
            return $content;
        }
        $pluginPattern = implode('|', $pluginNames);

        $pattern = '/
        \[
        (' . $pluginPattern . ')          # Plugin name
        :                               # Colon separator
        (\w+)                           # Shortcode name
        ([^\]]*)                        # Attributes string
        \]
        (?:([^\[]*?)\[\/\1:\2\])?      # Optional content and closing tag
    /x';

        return preg_replace_callback($pattern, function ($matches) {
            $pluginName = $matches[1];
            $shortcode = $matches[2];
            logger('debug plugin shortcode', [$pluginName, $shortcode]);
            $attrString = $matches[3] ?? '';
            $content = $matches[4] ?? '';

            if (!isset($this->managers[$pluginName])) {
                return $matches[0];
            }

            $manager = $this->managers[$pluginName];
            $attrs = $this->parseAttributes($attrString);

            if (!$manager->hasShortcode($shortcode)) {
                return $matches[0];
            }

            $handler = $manager->getShortcodeHandler($shortcode);
            return call_user_func($handler, $attrs, $content);
        }, $content);
    }


    /**
     * Simple attribute parser for shortcode attributes
     */
    protected function parseAttributes(string $text): array
    {
        $attrs = [];
        preg_match_all('/(\w+)="([^"]*)"/', $text, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $attrs[$match[1]] = $match[2];
        }
        return $attrs;
    }


}