<?php

namespace App\Theme;

use Illuminate\Console\Command;

class ThemeSetCommand extends Command
{
    protected $signature = 'theme:set {slug}';
    protected $description = 'Set active theme';

    public function handle(ThemeManager $themes)
    {
        $slug = $this->argument('slug');

        if (! $themes->exists($slug)) {
            $this->error("Theme not found: {$slug}");
            return 1;
        }

        config(['theme.active' => $slug]);

        $this->info("Theme set to: {$slug}");
        $this->info("To persist, set APP_THEME={$slug} in your .env or update config/theme.php");

        return 0;
    }
}
