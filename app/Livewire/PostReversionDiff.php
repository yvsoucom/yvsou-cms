<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
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

namespace App\Livewire;

use Livewire\Component;
use App\Models\PostReversion;
use App\Services\ReversionService;

class PostReversionDiff extends Component
{
    public $reversionId;
    public $reversion;
    public $diffHtml;

    public function mount($reversionId)
    {
        $this->reversionId = $reversionId;
        $this->reversion = PostReversion::findOrFail($reversionId);

        $service = new ReversionService();

        $oldContent = $service->reconstructHtmlPostVersion(
            $this->reversion->postid,
            max(0, $this->reversion->version - 1)
        );

        $newContent = $service->reconstructHtmlPostVersion(
            $this->reversion->postid,
            $this->reversion->version
        );

        $diffData = $service->compare($oldContent, $newContent);

        $this->diffHtml = $this->generateDiffHtml($oldContent, $diffData);
    }

    private function generateDiffHtml(string $oldContent, array $diffData): string
    {
        $service = new ReversionService();
        $baseLines = $service->normalizeHtmlToLines($oldContent);

        $htmlLines = [];
        $htmlLines[] = '<div class="overflow-x-auto">';
        $htmlLines[] = '<table class="w-full border-collapse border dark:border-gray-600">';
        $htmlLines[] = '  <thead>';
        $htmlLines[] = '    <tr class="bg-gray-100 dark:bg-gray-700">';
        $htmlLines[] = '      <th class="w-1/12 p-2 text-left border dark:border-gray-600">Type</th>';
        $htmlLines[] = '      <th class="w-5/12 p-2 text-left border dark:border-gray-600">Old Content</th>';
        $htmlLines[] = '      <th class="w-6/12 p-2 text-left border dark:border-gray-600">New Content</th>';
        $htmlLines[] = '    </tr>';
        $htmlLines[] = '  </thead>';
        $htmlLines[] = '  <tbody class="bg-white dark:bg-gray-800">';

        foreach ($diffData as $entry) {
            logger("reconstructModifiedFromDiff entry", $entry);
            switch ($entry['type']) {
                case 'inserted':
                    $htmlLines[] = '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 text-green-600 dark:text-green-400">' . $entry['relative_to'] . ' +</td>';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600"></td>';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 bg-green-50 dark:bg-green-900/30">' . e($entry['line']) . '</td>';
                    $htmlLines[] = '</tr>';
                    break;

                case 'modified':
                    $htmlLines[] = '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 text-yellow-600 dark:text-yellow-400">' . $entry['baseline_lineno'] . ' c</td>';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 bg-green-50 dark:bg-green-900/30">' . e($baseLines[$entry['baseline_lineno']]) . '</td>';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 bg-green-50 dark:bg-green-900/30">' . e($entry['line']) . '</td>';
                    $htmlLines[] = '</tr>';
                    break;

                case 'unchanged':
                    if (isset($entry['base_lineno']) && isset($baseLines[$entry['base_lineno']])) {
                        $htmlLines[] = '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">';
                        $htmlLines[] = '<td class="p-2 border dark:border-gray-600 text-gray-500 dark:text-gray-400">' . $entry['base_lineno'] . '</td>';
                        $htmlLines[] = '<td class="p-2 border dark:border-gray-600  text-gray-500 dark:text-gray-400">' . e($baseLines[$entry['base_lineno']]) . '</td>';
                        $htmlLines[] = '<td class="p-2 border dark:border-gray-600 text-gray-500 dark:text-gray-400">' . e($baseLines[$entry['base_lineno']]) . '</td>';
                        $htmlLines[] = '</tr>';
                    } else {
                        logger()->error("Invalid base_lineno in diff", $entry);
                    }
                    break;

                case 'deleted':
                    $htmlLines[] = '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 text-red-600 dark:text-red-400">' . $entry['base_lineno'] . ' -</td>';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600 bg-green-50 dark:bg-green-900/30">' . e($baseLines[$entry['base_lineno']]) . '</td>';
                    $htmlLines[] = '<td class="p-2 border dark:border-gray-600"></td>';
                    $htmlLines[] = '</tr>';
                    break;

                default:
                    logger()->warning("Unknown diff entry type: " . $entry['type']);
            }
        }

        $htmlLines[] = '  </tbody>';
        $htmlLines[] = '</table>';
        $htmlLines[] = '</div>';

        return implode("\n", $htmlLines);
    }

    public function render()
    {
        return view('livewire.post-reversion-diff');
    }
} 