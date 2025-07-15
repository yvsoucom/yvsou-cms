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
        $oldLines = preg_split('/\R/u', $oldContent);
        $htmlLines = [];

        // Index modifications by line number
        $modByLine = collect($diffData['modifications'] ?? [])->keyBy('line');

        // Prepare deletions and insertions grouped by start_line for quick lookup
        $deletions = collect($diffData['deletions'] ?? [])->groupBy('start_line');
        $insertions = collect($diffData['insertions'] ?? [])->groupBy('start_line');

        $totalLines = count($oldLines);

        for ($i = 0; $i <= $totalLines; $i++) {
            $lineNum = $i + 1;

            // Insertions before the first line or between lines (start_line means insert before that line)
            if ($insertions->has($lineNum)) {
                foreach ($insertions[$lineNum] as $ins) {
                    foreach ($ins['lines'] as $insLine) {
                        $htmlLines[] = '<div class="bg-green-100 text-green-700">+ ' . e($insLine) . '</div>';
                    }
                }
            }

            if ($i === $totalLines) {
                // We are past the last old line, no more lines to process
                break;
            }

            // Deleted lines: check if this line is fully deleted
            if ($deletions->has($lineNum)) {
                // One or more deleted chunks start here, show each chunk's deleted lines
                foreach ($deletions[$lineNum] as $del) {
                    foreach ($del['lines'] as $delLine) {
                        $htmlLines[] = '<del class="bg-red-100 text-red-700 block">- ' . e($delLine) . '</del>';
                    }
                }
                // Skip rendering the current old line as it is deleted
                continue;
            }

            // If not deleted, check for modification char-level highlighting
            if ($modByLine->has($lineNum)) {
                $charDiff = $modByLine[$lineNum]['char_diff'];
                $htmlLines[] = $this->highlightCharDiff($oldLines[$i], $charDiff);
            } else {
                // Normal line, no change
                $htmlLines[] = '<div>' . e($oldLines[$i]) . '</div>';
            }
        }

        return '<div class="diff-container space-y-1">' . implode("\n", $htmlLines) . '</div>';
    }

    private function highlightCharDiff(string $line, array $charDiff): string
    {
        $result = '';
        $pos = 0;
        $len = mb_strlen($line);

        $deletions = $charDiff['deletions'] ?? [];
        $insertions = $charDiff['insertions'] ?? [];

        usort($deletions, fn($a, $b) => $a['start_pos'] <=> $b['start_pos']);
        usort($insertions, fn($a, $b) => $a['start_pos'] <=> $b['start_pos']);

        $delIdx = 0;
        $insIdx = 0;

        while ($pos < $len) {
            while ($insIdx < count($insertions) && $insertions[$insIdx]['start_pos'] - 1 == $pos) {
                $result .= '<span class="bg-green-100 text-green-700">+ ' . e($insertions[$insIdx]['content']) . '</span>';
                $insIdx++;
            }

            if ($delIdx < count($deletions)) {
                $del = $deletions[$delIdx];
                $delStart = max($del['start_pos'] - 1, 0);
                $delEnd = max($del['end_pos'] - 1, 0);

                if ($pos < $delStart) {
                    $result .= e(mb_substr($line, $pos, $delStart - $pos));
                    $pos = $delStart;
                }

                if ($pos >= $delStart && $pos <= $delEnd) {
                    $deletedText = mb_substr($line, $delStart, $delEnd - $delStart + 1);
                    $result .= '<del class="bg-red-100 text-red-700">- ' . e($deletedText) . '</del>';
                    $pos = $delEnd + 1;
                    $delIdx++;
                    continue;
                }
            }

            if ($pos < $len) {
                $result .= e(mb_substr($line, $pos, 1));
                $pos++;
            }
        }

        while ($insIdx < count($insertions) && $insertions[$insIdx]['start_pos'] - 1 == $len) {
            $result .= '<span class="bg-green-100 text-green-700">+ ' . e($insertions[$insIdx]['content']) . '</span>';
            $insIdx++;
        }

        return '<div>' . $result . '</div>';
    }


    public function render()
    {
        return view('livewire.post-reversion-diff')->layout('layouts.app');
    }
}
