<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
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
        $oldLines = $service->normalizeHtmlToLines($oldContent);

        $htmlLines = [];
        $htmlLines[] = '<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
  <thead>
    <tr>
      <th style="width: 5%;">Type</th>
      <th style="width: 45%;">Old Content</th>
      <th style="width: 50%;">New Content</th>
    </tr>
  </thead>
  <tbody>';

        $modByLine = $diffData['modifications'] ?? [];
        usort($modByLine, fn($a, $b) => $a['line'] <=> $b['line']);

        $deletions = $diffData['deletions'] ?? [];
        usort($deletions, fn($a, $b) => $a['line'] <=> $b['line']);

        $insertions = $diffData['insertions'] ?? [];

        usort($insertions, function ($a, $b) {
            // Sort first by base line number
            if ($a['line'] === $b['line']) {
                // Then by original order in modified file
                return $a['relative_position'] <=> $b['relative_position'];
            }
            return $a['line'] <=> $b['line'];
        });
   

        $totalLines = count($oldLines);
        $insertionIndex = 0;

        for ($i = 1; $i <= $totalLines; $i++) {
            // Insertions BEFORE this line
            foreach ($insertions as $ins) {
                if ($ins['line'] === $i) {
                    while (
                        $insertionIndex < count($insertions) &&
                        $insertions[$insertionIndex]['line'] === $i
                    ) {
                        $ins = $insertions[$insertionIndex];
                        $htmlLines[] = '<tr><td style="color: green;">' . $i . ' +</td>';
                        $htmlLines[] = '<td></td>';
                        $htmlLines[] = '<td style="background-color: #dcfce7;">' . e($ins['content']) . '</td></tr>';
                        $insertionIndex++;
                    }
                    break;
                }
            }
            $matched = false;

            // Modifications take priority
            foreach ($modByLine as $mod) {
                if ($mod['line'] === $i) {
                    $htmlLines[] = '<tr><td style="color: orange;">' . $i . ' c</td>';
                    $htmlLines[] = '<td style="background-color: #fee2e2;">' . e($mod['base']) . '</td>';
                    $htmlLines[] = '<td style="background-color: #dcfce7;">' . e($mod['modified']) . '</td></tr>';
                    $matched = true;
                    break;
                }
            }

            // If not modified, check for deletion
            if (!$matched) {
                foreach ($deletions as $del) {
                    if ($del['line'] === $i) {
                        $htmlLines[] = '<tr><td style="color: red;">' . $i . ' -</td>';
                        $htmlLines[] = '<td style="background-color: #fee2e2;">' . e($del['content']) . '</td>';
                        $htmlLines[] = '<td></td></tr>';
                        $matched = true;
                        break;
                    }
                }
            }

            // If not matched, unchanged
            if (!$matched) {
                $htmlLines[] = '<tr><td>' . $i . '</td>';
                $htmlLines[] = '<td>' . e($oldLines[$i - 1]) . '</td>';
                $htmlLines[] = '<td>' . e($oldLines[$i - 1]) . '</td></tr>';
            }
        }

        // Insertions AFTER the last line
        while ($insertionIndex < count($insertions)) {
            $ins = $insertions[$insertionIndex];
            if ($ins['line'] > $totalLines) {
                $htmlLines[] = '<tr><td style="color: green;">' . ($totalLines + 1) . ' +</td>';
                $htmlLines[] = '<td></td>';
                $htmlLines[] = '<td style="background-color: #dcfce7;">' . e($ins['content']) . '</td></tr>';
            }
            $insertionIndex++;
        }

        $htmlLines[] = '</tbody></table>';
        return '<div class="diff-container space-y-1">' . implode("\n", $htmlLines) . '</div>';
    }



    public function render()
    {
        return view('livewire.post-reversion-diff')->layout('layouts.app');
    }
}
