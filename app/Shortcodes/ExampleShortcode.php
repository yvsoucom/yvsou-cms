<?php
// app/Shortcodes/ExampleShortcode.php

use function app\Helpers\add_shortcode;

// Register a global shortcode [hello]
add_shortcode('hello', function ($attrs) {
    $name = $attrs['name'] ?? 'World';
    return "Hello, {$name}!";
});
