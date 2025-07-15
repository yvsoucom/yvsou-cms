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

namespace App\Services;

use InvalidArgumentException;
use App\Models\PostReversion;
use Jfcherng\Diff\DiffHelper;
class ReversionService
{
    public function reconstructHtmlPostVersion(int $postId, int $targetVersion): string
    {
        logger("reconstructHtmlPostVersion", [$postId, $targetVersion]);
        // Fetch all reversions from version 0 up to and including the target version
        $reversions = PostReversion::where('postid', $postId)
            ->where('version', '<=', $targetVersion)
            ->orderBy('version')
            ->get();
        if ($reversions->isEmpty()) {
            throw new \Exception("No reversions found for post ID: $postId");
        }
        $basereversion = $reversions->first();
        if ($basereversion->version !== 0 || !$basereversion->base_content) {
            throw new \Exception("Missing or invalid base_content for version 0 of post ID: $postId");
        }
        $content = $basereversion->base_content;
        // Apply diffs in order from version 1 up to targetVersion
        foreach ($reversions->skip(1) as $reversion) {
            if (empty($reversion->diff)) {
                throw new \Exception("Invalid or missing diff for version {$reversion->version}.");
            }
            $rawDiff = $reversion->diff ?? '';
            $content = $this->reconstructFromDiffRanges($content, $rawDiff);
        }
        return $content;
    }


    public function generateCompareJson($baseText, $modifiedText)
    {
        $diff = $this->compare($baseText, $modifiedText);
        return json_encode($diff, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }


    public function compare(string $baseText, string $modifiedText): array
    {
        // Do whole text diff at line level
        $lineDiffJson = DiffHelper::calculate(
            $baseText,
            $modifiedText,
            'Json',
            [
                'detailLevel' => 'line',
                'resultForIdenticals' => [],
            ]
        );

        $blocks = json_decode($lineDiffJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON returned by DiffHelper");
        }

        $deletions = [];
        $insertions = [];
        $modifications = [];

        $lineNumber = 1;
        logger($blocks);
        foreach ($blocks as $hunk) {
            foreach ($hunk as $block) {
                $tagNum = $block['tag'] ?? '';
                $tag = match ($tagNum) {
                    0 => 'equal',
                    1 => 'insert',
                    2 => 'delete',
                    8 => 'replace',
                };
                if ($tag === 'equal') {
                    //   $lineNumber += substr_count($block['old'], "\n") + 1;
                    continue;
                }

                if ($tag === 'delete') {
                    $deletions[] = [
                        'start_line' => $block['old']['offset'] + 1,
                        'lines' => $block['old']['lines'],
                    ];
                    //  $lineNumber += count($lines);
                }

                if ($tag === 'insert') {

                    $insertions[] = [
                        'start_line' => $block['new']['offset'] + 1,
                        'lines' => $block['new']['lines'],
                    ];

                }

                if ($tag === 'replace') {

                    // For each line that was replaced
                    foreach ($block['old']['lines'] as $i => $oldLine) {
                        $newLine = $block['new']['lines'][$i] ?? '';

                        $charDiffJson = DiffHelper::calculate(
                            $oldLine,
                            $newLine,
                            'Json',
                            [
                                'detailLevel' => 'char',
                                'resultForIdenticals' => [],
                            ]
                        );

                        $charDiffRaw = json_decode($charDiffJson, true);
                        $charDiff = $this->convertJfcherngJsonToRanges($charDiffRaw);
                        if (!empty($charDiff['insertions']) || !empty($charDiff['deletions'])) {
                            $modifications[] = [
                                'line' => $block['old']['offset'] + 1 + $i,
                                'char_diff' => $charDiff,
                            ];
                        }

                    }

                }
            }
        }
        return [
            'deletions' => $deletions,
            'insertions' => $insertions,
            'modifications' => $modifications,
        ];
    }


    private function convertJfcherngJsonToRanges(array $jfcherngDiff): array
    {
        $insertions = [];
        $deletions = [];
        $oldPos = 0;
        $newPos = 0;

        foreach ($jfcherngDiff as $block) {
            $tag = $block['tag'] ?? '';

            $oldText = $block['old'] ?? '';
            $newText = $block['new'] ?? '';

            if ($tag === 'equal') {
                $oldPos += mb_strlen($oldText);
                $newPos += mb_strlen($newText);
            }

            if ($tag === 'delete') {
                $deletions[] = [
                    'start_pos' => $oldPos + 1,
                    'end_pos' => $oldPos + mb_strlen($oldText),
                ];
                $oldPos += mb_strlen($oldText);
            }

            if ($tag === 'insert') {
                $insertions[] = [
                    'start_pos' => $newPos + 1,
                    'content' => $newText,
                ];
                $newPos += mb_strlen($newText);
            }

            if ($tag === 'replace') {
                if ($oldText !== '') {
                    $deletions[] = [
                        'start_pos' => $oldPos + 1,
                        'end_pos' => $oldPos + mb_strlen($oldText),
                    ];
                }
                if ($newText !== '') {
                    $insertions[] = [
                        'start_pos' => $newPos + 1,
                        'content' => $newText,
                    ];
                }
                $oldPos += mb_strlen($oldText);
                $newPos += mb_strlen($newText);
            }
        }

        return [
            'insertions' => $insertions,
            'deletions' => $deletions,
        ];
    }

    public function reconstructFromDiffRanges(string $baseContent, string $jsonDiff): string
    {
        $diffData = json_decode($jsonDiff, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Invalid JSON diff data");
        }

        $baseLines = preg_split('/\R/u', $baseContent);

        // 🔴 1. Apply deletions (lines): from bottom to top
        $deletions = $diffData['deletions'] ?? [];
        usort($deletions, fn($a, $b) => $b['start_line'] <=> $a['start_line']);

        foreach ($deletions as $del) {
            $startLine = max($del['start_line'] - 1, 0);
            array_splice($baseLines, $startLine, count($del['lines']));
        }

        // 🟢 2. Apply insertions (lines): from top to bottom
        $insertions = $diffData['insertions'] ?? [];
        usort($insertions, fn($a, $b) => $a['start_line'] <=> $b['start_line']);

        foreach ($insertions as $ins) {
            $startLine = max($ins['start_line'] - 1, 0);
            array_splice($baseLines, $startLine, 0, $ins['lines']);
        }

        // 🟡 3. Apply modifications (char-level)
        $modifications = $diffData['modifications'] ?? [];

        foreach ($modifications as $mod) {
            $lineIdx = $mod['line'] - 1;

            if (!isset($baseLines[$lineIdx])) {
                continue; // Line was deleted by previous operations
            }

            $line = $baseLines[$lineIdx];
            $charDiff = $mod['char_diff'] ?? [];

            // 🔴 3a. Deletions inside line
            $dels = $charDiff['deletions'] ?? [];
            usort($dels, fn($a, $b) => $b['start_pos'] <=> $a['start_pos']);

            foreach ($dels as $del) {
                $start = max($del['start_pos'] - 1, 0);
                $length = max($del['end_pos'] - $del['start_pos'] + 1, 0);

                $line = mb_substr($line, 0, $start) . mb_substr($line, $start + $length);
            }

            // 🟢 3b. Insertions inside line
            $insArr = $charDiff['insertions'] ?? [];
            usort($insArr, fn($a, $b) => $a['start_pos'] <=> $b['start_pos']);

            foreach ($insArr as $ins) {
                $start = max($ins['start_pos'] - 1, 0);

                $line = mb_substr($line, 0, $start) . $ins['content'] . mb_substr($line, $start);
            }

            $baseLines[$lineIdx] = $line;
        }

        return implode("\n", $baseLines);
    }


}

