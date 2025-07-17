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
        $baseLines = $service->normalizeHtmlToLines($oldContent);



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


        foreach ($diffData as $entry) {
            logger("reconstructModifiedFromDiff entry", $entry);
            switch ($entry['type']) {
                case 'inserted':
                    $htmlLines[] = '<tr><td style="color: green;">' . $entry['relative_to'] . ' +</td>';
                    $htmlLines[] = '<td></td>';
                    $htmlLines[] = '<td style="background-color: #dcfce7;">' . e($entry['line']) . '</td></tr>';

                    break;
                case 'modified':
                    $htmlLines[] = '<tr><td style="color: orange;">' . $entry['baseline_lineno'] . ' c</td>';
                    $htmlLines[] = '<td style="background-color: #fee2e2;">' . e($baseLines[$entry['baseline_lineno']])  . '</td>';
                    $htmlLines[] = '<td style="background-color: #dcfce7;">' . e($entry['line']) . '</td></tr>';

                    break;

                case 'unchanged':
                    if (isset($entry['base_lineno']) && isset($baseLines[$entry['base_lineno']])) {
                        $htmlLines[] = '<tr><td>' . $entry['base_lineno'] . '</td>';
                        $htmlLines[] = '<td>' . e($baseLines[$entry['base_lineno']]) . '</td>';
                        $htmlLines[] = '<td>' . e($baseLines[$entry['base_lineno']]) . '</td></tr>';

                    } else {
                        logger()->error("Invalid base_lineno in diff", $entry);
                    }
                    break;

                // Optional: for robustness
                case 'deleted':
                    $htmlLines[] = '<tr><td style="color: red;">' . $entry['base_lineno'] . ' -</td>';
                    $htmlLines[] = '<td style="background-color: #fee2e2;">' . e($baseLines[$entry['base_lineno']]) . '</td>';
                    $htmlLines[] = '<td></td></tr>';

                    break;

                default:
                    logger()->warning("Unknown diff entry type: " . $entry['type']);
            }
        }


        $htmlLines[] = '</tbody></table>';
        return '<div class="diff-container space-y-1">' . implode("\n", $htmlLines) . '</div>';
    }



    public function render()
    {
        return view('livewire.post-reversion-diff')->layout('layouts.app');
    }
}
