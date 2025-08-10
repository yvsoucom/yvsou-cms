<?php

namespace App\Theme;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ThemePublishAssetsCommand extends Command
{
    protected $signature = 'theme:publish-assets {slug?}';
    protected $description = 'Publish theme assets to public/themes/{slug}';

    public function handle(ThemeManager $themes)
    {
        $slug = $this->argument('slug') ?: $themes->active();

        if (! $themes->exists($slug)) {
            $this->error("Theme not found: {$slug}");
            return 1;
        }

        $from = $themes->path($slug) . '/assets';
        $to = public_path('themes/' . $slug);

        if (! File::exists($from)) {
            $this->info('No assets to publish for this theme.');
            return 0;
        }

        File::ensureDirectoryExists($to);
        File::copyDirectory($from, $to);

        $this->info("Published assets to: {$to}");
        return 0;
    }
}
