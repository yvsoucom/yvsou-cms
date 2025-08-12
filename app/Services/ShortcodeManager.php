<?php
 

// app/Services/ShortcodeManager.php
namespace App\Services;

use App\Models\Shortcode;
use Illuminate\Support\Facades\Schema;
class ShortcodeManager
{
    protected array $shortcodes = [];

    public function register(string $tag, callable $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

    /**
     * Process content, replacing shortcodes with callback results.
     * 
     * @param string $content
     * @param array $context Optional context passed to shortcode callbacks
     * @return string
     */
    public function process(string $content, array $context = []): string
    {
        // Simple regex to find [shortcode attr="value"] patterns
        return preg_replace_callback('/\[([a-zA-Z0-9_]+)([^\]]*)\]/', function ($matches) use ($context) {
            $tag = $matches[1];
            $attrString = $matches[2];

            if (!isset($this->shortcodes[$tag])) {
                return $matches[0]; // Unknown shortcode: leave as is
            }

            // Parse attributes into array
            $attrs = $this->parseAttributes($attrString);

            // Call the shortcode callback with attributes and context
            return call_user_func($this->shortcodes[$tag], $attrs, $context);
        }, $content);
    }

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
 