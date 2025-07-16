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
        // $oldLines = preg_split('/\R/u', $oldContent);
        $service = new ReversionService();
        $oldLines = $service->normalizeHtmlToLines($oldContent);

        $htmlLines = [];
        $htmlLines[] = '<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
  <thead>
    <tr>
      <th style="width: 5%;">type</th>
      <th style="width: 45%;">Content</th>
      <th style="width: 50%;">newContent</th>
    </tr>
  </thead>
  <tbody>';
        // Index modifications by line number
        $modByLine = $diffData['modifications'] ?? [];
        usort($modByLine, fn($a, $b) => $a['line'] <=> $b['line']);

        $deletions = $diffData['deletions'] ?? [];
        usort($deletions, fn($a, $b) => $b['line'] <=> $a['line']);

        $insertions = $diffData['insertions'] ?? [];
        usort($insertions, fn($a, $b) => $a['line'] <=> $b['line']);

        $totalLines = count($oldLines);

        for ($i = 0; $i < $totalLines; $i++) {


            // Insertions before the first line or between lines (start_line means insert before that line)

            foreach ($insertions as $ins) {
                $startLine = $ins['line'];
                if ($startLine === $i) {
                    $htmlLines[] = '<tr><td>+</td>';
                    $htmlLines[] = '<td>     </td>';
                    $htmlLines[] = '<td><div class="bg-green-100 text-green-700">' . e($ins['content']) . '</div></td></tr>';

                    continue;
                }

            }



            // Deleted lines: check if this line is fully deleted

            foreach ($deletions as $del) {
                $startLine = $del['line'];
                if ($startLine === $i) {
                    $htmlLines[] = '<tr><td>-</td>';
                    $htmlLines[] = '<td><del class="bg-red-100 text-red-700 block">' . e($del['content']) . '</del></td></tr>';

                    continue;
                }

            }

            foreach ($modByLine as $mod) {
                $startLine = $mod['line'];
                if ($startLine === $i) {
                    $htmlLines[] = '<tr><td>c</td>';
                    $htmlLines[] = '<td style="background-color: #fee2e2;">' . e($mod['base']) . '</td>';   // Red for deletion
                    $htmlLines[] = '<td style="background-color: #dcfce7;">' . e($mod['modified']) . '</td>'; // Green for insertion
                    $htmlLines[] = '</tr>';
                    continue;
                }
            }


            // Normal line, no change
            $htmlLines[] = '<tr><td> </td>';
             $htmlLines[] = '<td><div>' . e($oldLines[$i]) . '</div></td>';

            $htmlLines[] = '<td><div>' . e($oldLines[$i]) . '</div></td></tr>';

        }
        $htmlLines[] = ' </tbody></table>';
        return '<div class="diff-container space-y-1">' . implode("\n", $htmlLines) . '</div>';
    }



    public function render()
    {
        return view('livewire.post-reversion-diff')->layout('layouts.app');
    }
}
