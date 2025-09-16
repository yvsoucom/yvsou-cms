<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.,     All rights reserved.
 * Author: Lican Huang
 *
 * SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary  
 * License: Dual Licensed – GPLv3 or Commercial
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * As an alternative to GPLv3, commercial licensing is available for organizations
 * or individuals requiring proprietary usage, private modifications, or support.
 *
 * Contact: yvsoucom@gmail.com
 * GPL License: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Log;
use PhpZip\ZipFile;
use RuntimeException;
use Throwable;
use Illuminate\Support\Facades\Artisan;
class AutoUpdaterService
{
    protected string $repo;
    protected string $app_version;

    public function __construct()
    {
        $this->repo = config('version.github_repo');
        $this->app_version = config('version.app_version');

    }
    public function checkLatestVersion(): ?array
    {
        $response = Http::get("https://api.github.com/repos/{$this->repo}/releases/latest");

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function isOutdated()
    {
        $release = $this->checkLatestVersion();
        logger(" release verion");

        $latest = $release['tag_name'];
        logger($latest);
        $current = $this->app_version;

        logger(" curent verion");
        logger($current);
        Log::debug('Current version: [' . $current . ']');
        Log::debug('Latest version: [' . $latest . ']');
        Log::debug('Compare: ' . (version_compare($current, $latest, '<') ? 'true' : 'false'));

        if ($latest && version_compare($current, $latest, '<')) {
            logger($latest);
            return [
                'outdated' => true,
                'latest' => $latest,
                'current' => $current,
            ];
        }

        return [
            'outdated' => false,
            'latest' => $latest,
            'current' => $current,
        ];
    }


    public function downloadLatestZip(): ?string
    {
        $release = $this->checkLatestVersion();
        if (!$release) {
            return null;
        }

        $outdated = $this->isOutdated();
        if (!$outdated['outdated']) {
            return null;
        }

        $asset = collect($release['assets'] ?? [])
            ->firstWhere('name', 'installvendor-' . $release['tag_name'] . '.zip');

        if (!$asset) {
            \Log::warning("No matching ZIP asset found for tag {$release['tag_name']}");
            return null;
        }

        $zipUrl = $asset['browser_download_url'];
        $fileName = 'update-' . $release['tag_name'] . '.zip';
        $tempPath = storage_path("app/{$fileName}");

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
        ])->sink($tempPath)->get($zipUrl);

        if ($response->failed()) {
            \Log::error("Failed to download ZIP from GitHub: {$zipUrl}");
            return null;
        }

        return $tempPath;
    }


    public function runPostUpdate(): bool
    {
        // exec('composer install --no-dev --optimize-autoloader');
        try {
            \Artisan::call('migrate', [
                '--force' => true // run without confirmation in production
            ]);
        } catch (\Exception $e) {
            Log::error("❌ Post-update migrate db fail: " . $e->getMessage());

            return false;
        }
        try {
            // Clear caches
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
            Log::info("Post-update complete.");
        } catch (\Exception $e) {
            Log::error("❌ Post-update failed: " . $e->getMessage());
            return false;
        }
        return true;
    }


    public function applyUpdate(): bool
    {
        Log::info('[Updater] Starting update process...');

        $zipPath = $this->downloadLatestZip();
        if (!$zipPath) {
            Log::error('[Updater] No update zip was downloaded.');
            return false;
        }
        Log::info("[Updater] Downloaded update zip to: {$zipPath}");

        return $this->updateFromZip($zipPath, base_path());

    }

    /** Recursive copy (overwrite) */
    private function copyRecursive($src, $dst, $exclude = [])
    {
        $items = scandir($src);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..')
                continue;

            $srcPath = $src . '/' . $item;
            $dstPath = $dst . '/' . $item;

            foreach ($exclude as $skip) {
                if ($item === $skip)
                    continue 2;
            }

            if (is_dir($srcPath)) {
                if (!is_dir($dstPath))
                    mkdir($dstPath, 0755, true);
                $this->copyRecursive($srcPath, $dstPath, $exclude);
            } else {
                copy($srcPath, $dstPath);
            }
        }
    }

    public function updateFromZip($zipPath, $destination)
    {
        $exclude = ['.env', 'storage', 'bootstrap/cache', 'config'];
        $excludebackup = ['storage'];
        $tempDir = storage_path('app/tmp_update');
        $backupDir = storage_path('app/backup_' . date('Ymd_His'));

        // 1. Backup old app
        $this->copyRecursive(base_path(), $backupDir, $excludebackup);

        // 1. Extract ZIP to temp folder
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }
        File::makeDirectory($tempDir, 0755, true);

        $zip = new \ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($tempDir);
            $zip->close();
        } else {
            throw new \Exception("Cannot open ZIP file: $zipPath");
        }

        // Detect if ZIP has a single root folder
        $dirs = File::directories($tempDir);
        if (count($dirs) === 1) {
            $tempDir = $dirs[0];
        }

        // 2. Overwrite files first
        $this->copyFiles($tempDir, $destination, $exclude);

        // 3. Delete files not in ZIP
        $this->deleteExtraFiles($destination, $tempDir, $exclude);

        // Cleanup temp folder
        File::deleteDirectory(storage_path('app/tmp_update'));
        $this->runPostUpdate();
        return true;
    }

    private function copyFiles($source, $destination, $exclude)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Skip excluded paths
            foreach ($exclude as $skip) {
                if (str_starts_with($relativePath, $skip)) {
                    continue 2;
                }
            }

            $target = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($file->isDir()) {
                if (!file_exists($target))
                    mkdir($target, 0755, true);
            } else {
                copy($file->getPathname(), $target);
            }
        }
    }

    private function deleteExtraFiles($destination, $source, $exclude)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($destination, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        $sourceFiles = collect(File::allFiles($source))
            ->map(fn($f) => str_replace($source . DIRECTORY_SEPARATOR, '', $f->getPathname()))
            ->toArray();

        foreach ($iterator as $file) {
            $relativePath = str_replace($destination . DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Skip excluded paths
            foreach ($exclude as $skip) {
                if (str_starts_with($relativePath, $skip)) {
                    continue 2;
                }
            }

            if (!in_array($relativePath, $sourceFiles)) {
                $file->isDir() ? @rmdir($file->getPathname()) : @unlink($file->getPathname());
            }
        }
    }


}
