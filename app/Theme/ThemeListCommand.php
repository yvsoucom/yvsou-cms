<?php

namespace App\Theme;

use Illuminate\Console\Command;

class ThemeListCommand extends Command
{
    protected $signature = 'theme:list';
    protected $description = 'List available themes';

    public function handle(ThemeManager $themes)
    {
        $all = $themes->all();
        if (empty($all)) {
            $this->info('No themes found in resources/themes');
            return 0;
        }

        $rows = [];
        foreach ($all as $slug => $meta) {
            $rows[] = [$slug, $meta['name'] ?? '-', $meta['version'] ?? '-', $slug === $themes->active() ? '*' : ''];
        }

        $this->table(['Slug', 'Name', 'Version', 'Active'], $rows);
        return 0;
    }
}
