<?php
/**
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-07-02
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




    public function backupCurrentCopy(): ?string
    {
        $backupDirName = 'backup-' . date('YmdHis');
        $backupPath = storage_path("app/backups/{$backupDirName}");

        // Create the backup directory
        File::makeDirectory($backupPath, 0755, true);

        $sourcePath = base_path();

        // Exclude these folders or files if needed
        $exclude = ['storage', '.env', 'node_modules', '.git'];

        $this->recursiveCopyWithExclude($sourcePath, $backupPath, $exclude);

        Log::info("Backup copied to: {$backupPath}");

        return $backupPath;
    }

    protected function recursiveCopyWithExclude($src, $dst, $exclude = [])
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (in_array($file, $exclude)) {
                continue;
            }

            $srcPath = "{$src}/{$file}";
            $dstPath = "{$dst}/{$file}";

            if (is_dir($srcPath)) {
                $this->recursiveCopyWithExclude($srcPath, $dstPath, $exclude);
            } else {
                copy($srcPath, $dstPath);
            }
        }

        closedir($dir);
    }

    function extractZipFile($zipPath, $extractPath)
    {
        try {
            $zipFile = new ZipFile();
            $zipFile->openFile($zipPath) // or openFromString($zipContent)
                ->extractTo($extractPath);
        } catch (\PhpZip\Exception\ZipException $e) {
            // Handle error
            error_log('Zip extraction failed: ' . $e->getMessage());
        } finally {
            // Always close the archive to free resources
            if (isset($zipFile)) {
                $zipFile->close();
            }
        }
    }
    public function extractZip(string $zipPath): ?string
    {
        $extractPath = base_path('update-temp');

        // Clean up any previous temp
        if (\File::exists($extractPath)) {
            \File::deleteDirectory($extractPath);
        }
        \File::makeDirectory($extractPath, 0755, true);

        $extracted = false;

        // ✅ Try ZipArchive if available
        if (class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive;
            if ($zip->open($zipPath) === true) {
                if ($zip->extractTo($extractPath)) {
                    Log::info("Extracted ZIP using ZipArchive to {$extractPath}");
                    $extracted = true;
                } else {
                    Log::warning("ZipArchive could not extract {$zipPath}");
                }
                $zip->close();
            } else {
                Log::warning("ZipArchive could not open {$zipPath}");
            }
        } else {
            Log::info("ZipArchive class not available, trying shell unzip...");
        }

        if (!$extracted) {
            if ($this->extractZipFile($zipPath, $extractPath)) {
                Log::info("Extracted ZIP using ZipFile to {$extractPath}");
                $extracted = true;
            } else {
                Log::warning("ZipFile could not extract {$zipPath}");
            }
        }

        // ✅ Fallback to shell unzip if needed
        if (!$extracted) {
            if ($this->fallbackShellUnzip($zipPath, $extractPath)) {
                Log::info("Extracted ZIP using ShellUnzip to {$extractPath}");
                $extracted = true;
            } else {
                Log::warning("ShellUnzip could not extract {$zipPath}");
            }
        }

        return $extracted ? $extractPath : null;
    }

    protected function fallbackShellUnzip(string $zipPath, string $extractPath): bool
    {
        if (File::exists($extractPath)) {
            File::deleteDirectory($extractPath);
        }

        File::makeDirectory($extractPath, 0755, true);

        $command = sprintf(
            'unzip -o %s -d %s 2>&1',
            escapeshellarg($zipPath),
            escapeshellarg($extractPath)
        );

        $output = [];
        $result = null;

        exec($command, $output, $result);

        Log::debug("Shell unzip output: " . implode("\n", $output));
        Log::debug("Shell unzip return code: {$result}");

        return $result === 0;
    }


    public function overwriteWithExtract(string $extractPath): bool
    {

        if ($this->recursiveCopy($extractPath, base_path())) {
            File::deleteDirectory($extractPath);
            Log::info("Overwrite complete.");
            return true;
        }
        Log::info("Overwrite fail.");
        return false;
    }


    public function runPostUpdate(): bool
    {
        // exec('composer install --no-dev --optimize-autoloader');
        try {
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');

            Log::info("Post-update complete.");
            return true;
        } catch (\Exception $e) {
            Log::error("❌ Post-update failed: " . $e->getMessage());
        }
        return false;

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

        $backupResult = $this->backupCurrentCopy();
        if (!$backupResult) {
            Log::warning('[Updater] Backup failed or not created.');
        } else {
            Log::info("[Updater] Backup created at: {$backupResult}");
        }

        $extractPath = $this->extractZip($zipPath);
        if (!$extractPath) {
            Log::error('[Updater] Extraction failed.');
            return false;
        }
        Log::info("[Updater] Extracted update to: {$extractPath}");

        $overwriteResult = $this->overwriteWithExtract($extractPath);
        Log::info("[Updater] Overwrite result: " . ($overwriteResult ? 'success' : 'failed'));
        if ($overwriteResult) {
            if ($this->runPostUpdate()) {
                Log::info('[Updater] Post update commands executed.');
                Log::info('[Updater] Update process completed successfully.');
                return true;
            } else {
                Log::info('[Updater] Post update commands executed.');
                Log::info('[Updater] Update process fail.');
                return false;
            }

        }

        return false;
    }


    /**
     * Recursively copies files with proper path handling and error checking
     */
    protected function recursiveCopy(string $source, string $destination): bool
    {
        try {
            // Normalize paths
            $source = rtrim($source, DIRECTORY_SEPARATOR);
            $destination = rtrim($destination, DIRECTORY_SEPARATOR);

            // Check if source exists
            if (!file_exists($source)) {
                throw new RuntimeException("Source directory does not exist: {$source}");
            }

            // Handle the case where update package contains root folder
            if (basename($source) === 'yvsou-cms') {
                $destination = dirname($destination);
                Log::debug("Adjusting destination path to prevent nesting: {$destination}");
            }

            // Create destination directory if needed
            if (!is_dir($destination)) {
                if (!mkdir($destination, 0755, true)) {
                    throw new RuntimeException("Failed to create directory: {$destination}");
                }
            }

            $dir = opendir($source);
            if ($dir === false) {
                throw new RuntimeException("Failed to open source directory: {$source}");
            }

            while (($file = readdir($dir)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $srcPath = $source . DIRECTORY_SEPARATOR . $file;
                $dstPath = $destination . DIRECTORY_SEPARATOR . $file;

                if (is_dir($srcPath)) {
                    if (!$this->recursiveCopy($srcPath, $dstPath)) {
                        throw new RuntimeException("Failed to copy directory: {$srcPath}");
                    }
                } else {
                    if (chmod($dstPath, 0777)) {
                        echo "Permissions changed temp 777 successfully";

                        if (!copy($srcPath, $dstPath)) {
                            chmod($dstPath, 0755);
                            throw new RuntimeException("Failed to copy file: {$srcPath}");
                        }
                        // Maintain original file permissions
                        chmod($dstPath, 0755);
                    }
                }
            }

            closedir($dir);
            return true;

        } catch (Throwable $e) {
            Log::error("Recursive copy failed: " . $e->getMessage());
            return false;
        }
    }
    /*  protected function recursiveCopy($src, $dst)
      {
            // Normalize paths
          $src = rtrim($src, '/');
          $dst = rtrim($dst, '/');

          // Check if source exists
          if (!is_dir($src)) {
              throw new \RuntimeException("Source directory not found: {$src}");
          }

          // Handle package root directory
          $srcBasename = basename($src);
          if ($srcBasename === 'yvsou-cms') {
              $dst = dirname($dst); // Move contents up one level
          }

          $dir = opendir($src);
          @mkdir($dst, 0755, true);
          logger("recursiveCopy",[$src,$dst]) ;
          while (false !== ($file = readdir($dir))) {
              if (($file !== '.') && ($file !== '..')) {
                  $srcPath = $src . '/' . $file;
                  $dstPath = $dst . '/' . $file;

                  if (is_dir($srcPath)) {
                      $this->recursiveCopy($srcPath, $dstPath);
                  } else {
                      copy($srcPath, $dstPath);
                  }
              }
          }
          closedir($dir);
      }
          */
}
