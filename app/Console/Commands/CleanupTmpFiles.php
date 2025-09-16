<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2025-09-05
*
* SPDX-License-Identifier: GPL-3.0-or-later
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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTmpFiles extends Command
{
    protected $signature = 'tmp:cleanup';
    protected $description = 'Delete temporary files older than 30 days';

    public function handle()
    {
        $disk = Storage::disk('local');
        $baseDir = 'tmp';

        $threshold = Carbon::now()->subDays(30)->timestamp;

        $directories = $disk->allDirectories($baseDir);

        foreach ($directories as $dir) {
            $files = $disk->files($dir);

            foreach ($files as $file) {
                $fullPath = storage_path("app/{$file}");
                if (!file_exists($fullPath)) {
                    continue;
                }

                $mtime = filemtime($fullPath);
                if ($mtime < $threshold) {
                    $this->info("Deleting expired tmp file: {$file}");
                    $disk->delete($file);
                }
            }

            // Remove empty directories
            if (empty($disk->files($dir))) {
                $disk->deleteDirectory($dir);
            }
        }

        $this->info('Tmp cleanup complete.');
    }
}
 