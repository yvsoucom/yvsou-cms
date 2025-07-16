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


    public function lineDiffLCS(string $baseText, string $modifiedText): array
    {
        $baseLines = $this->normalizeHtmlToLines($baseText);
        $modLines = $this->normalizeHtmlToLines($modifiedText);

        $m = count($baseLines);
        $n = count($modLines);

        // Step 1: Build LCS table
        $lcs = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));
        for ($i = $m - 1; $i >= 0; $i--) {
            for ($j = $n - 1; $j >= 0; $j--) {
                if ($baseLines[$i] === $modLines[$j]) {
                    $lcs[$i][$j] = $lcs[$i + 1][$j + 1] + 1;
                } else {
                    $lcs[$i][$j] = max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
                }
            }
        }

        // Step 2: Walk through to get diff
        $i = $j = 0;
        $result = [
            'insertions' => [],
            'deletions' => [],
            'modifications' => [],
        ];
        $relative = 1;

        while ($i < $m && $j < $n) {
            if ($baseLines[$i] === $modLines[$j]) {
                $i++;
                $j++;
                $relative = 1;
            } elseif ($lcs[$i + 1][$j] >= $lcs[$i][$j + 1]) {
                $result['deletions'][] = [
                    'line' => $i + 1,
                    'content' => $baseLines[$i],
                ];
                $i++;
                $relative = 1;
            } elseif ($lcs[$i + 1][$j] < $lcs[$i][$j + 1]) {
                $result['insertions'][] = [
                    'line' => $i + 1,
                    'content' => $modLines[$j],
                    'relative_position' => $relative++,
                ];
                $j++;
            }
        }

        // Handle remaining lines
        while ($i < $m) {
            $result['deletions'][] = [
                'line' => $i + 1,
                'content' => $baseLines[$i],
            ];
            $i++;
        }

        while ($j < $n) {
            $result['insertions'][] = [
                'line' => $i + 1,
                'content' => $modLines[$j],
                'relative_position' => $relative++,
            ];
            $j++;
        }

        // Step 3: Detect modification pairs (delete + insert at same line)
        $mods = [];
        foreach ($result['deletions'] as $dKey => $del) {
            foreach ($result['insertions'] as $iKey => $ins) {
                if ($del['line'] === $ins['line']) {
                    $mods[] = [
                        'line' => $del['line'],
                        'base' => $del['content'],
                        'modified' => $ins['content'],
                    ];
                    unset($result['deletions'][$dKey]);
                    unset($result['insertions'][$iKey]);
                    break;
                }
            }
        }

        $result['modifications'] = array_merge($result['modifications'], $mods);
        $result['deletions'] = array_values($result['deletions']);
        $result['insertions'] = array_values($result['insertions']);


        $expectedLineCount = count($baseLines)
            + count($result['insertions'])
            - count($result['deletions']);

        $actualModifiedLineCount = count($modLines);

        if ($expectedLineCount !== $actualModifiedLineCount) {
            logger()->warning('LineDiff mismatch detected', [
                'baseLineCount' => count($baseLines),
                'insertionCount' => count($result['insertions']),
                'deletionCount' => count($result['deletions']),
                'expectedModifiedLineCount' => $expectedLineCount,
                'actualModifiedLineCount' => $actualModifiedLineCount,
                'modificationCount' => count($result['modifications']),
                'insertions' => $result['insertions'],
                'deletions' => $result['deletions'],
                'modifications' => $result['modifications'],
            ]);
        }

        return $result;
    }

    public function LineDiffWithBaseLineNumbers($baseText, $modifiedText)
    {
        $baseLines = $this->normalizeHtmlToLines($baseText);
        $modifiedLines = $this->normalizeHtmlToLines($modifiedText);

        $result = [
            'insertions' => [],
            'deletions' => [],
            'modifications' => [],
        ];

        $basePointer = 0;
        $modifiedPointer = 0;

        while ($basePointer < count($baseLines) || $modifiedPointer < count($modifiedLines)) {
            $baseLine = $baseLines[$basePointer] ?? null;
            $modifiedLine = $modifiedLines[$modifiedPointer] ?? null;

            // ✅ If same, move on
            if ($baseLine === $modifiedLine) {
                $basePointer++;
                $modifiedPointer++;
                continue;
            }

            // ✅ Try to match future sync points
            $nextMatch = $this->findNextMatch($baseLines, $modifiedLines, $basePointer, $modifiedPointer);

            $baseGap = $nextMatch['basePos'] - $basePointer;
            $modGap = $nextMatch['modifiedPos'] - $modifiedPointer;

            $maxGap = max($baseGap, $modGap);

            // ✅ Process line-by-line to extract modifications or partial inserts/deletes
            for ($i = 0; $i < $maxGap; $i++) {
                $baseLine = $baseLines[$basePointer + $i] ?? null;
                $modLine = $modifiedLines[$modifiedPointer + $i] ?? null;

                if ($baseLine !== null && $modLine !== null) {
                    // ✅ Modification
                    $result['modifications'][] = [
                        'line' => $basePointer + $i + 1,
                        'base' => $baseLine,
                        'modified' => $modLine,
                    ];
                } elseif ($baseLine !== null) {
                    // ✅ Pure deletion
                    $result['deletions'][] = [
                        'line' => $basePointer + $i + 1,
                        'content' => $baseLine,
                    ];
                } elseif ($modLine !== null) {
                    // ✅ Pure insertion
                    $insertLine = $basePointer + 1;
                    if ($insertLine > count($baseLines)) {
                        $insertLine = count($baseLines) + 1;
                    }

                    $result['insertions'][] = [
                        'line' => $insertLine,
                        'content' => $modLine,
                        'relative_position' => $i + 1,
                    ];
                }
            }

            $basePointer = $nextMatch['basePos'];
            $modifiedPointer = $nextMatch['modifiedPos'];
        }

        /*    assert(
                count($modifiedLines) === count($baseLines)
                + count($result['insertions'])
                - count($result['deletions'])
            );
            */
        $expectedLineCount = count($baseLines)
            + count($result['insertions'])
            - count($result['deletions']);

        $actualModifiedLineCount = count($modifiedLines);

        if ($expectedLineCount !== $actualModifiedLineCount) {
            logger()->warning('LineDiff mismatch detected', [
                'baseLineCount' => count($baseLines),
                'insertionCount' => count($result['insertions']),
                'deletionCount' => count($result['deletions']),
                'expectedModifiedLineCount' => $expectedLineCount,
                'actualModifiedLineCount' => $actualModifiedLineCount,
                'modificationCount' => count($result['modifications']),
                'insertions' => $result['insertions'],
                'deletions' => $result['deletions'],
                'modifications' => $result['modifications'],
            ]);
        }


        return $result;
    }


    private function findNextMatch(array $base, array $modified, int $basePos, int $modifiedPos, int $window = 5): array
    {
        $baseLength = count($base);
        $modifiedLength = count($modified);

        for ($i = $basePos; $i < min($basePos + $window, $baseLength); $i++) {
            for ($j = $modifiedPos; $j < min($modifiedPos + $window, $modifiedLength); $j++) {
                if ($base[$i] === $modified[$j]) {
                    return [
                        'basePos' => $i,
                        'modifiedPos' => $j,
                    ];
                }
            }
        }

        // No match found; assume end
        return [
            'basePos' => $baseLength,
            'modifiedPos' => $modifiedLength,
        ];
    }




    /**
     * Normalize HTML content to an array of "lines"
     * where each <p> is a line, and <br> splits inside paragraphs.
     *
     * @param string $html
     * @return array
     */
    public function normalizeHtmlToLines(string $html): array
    {
        $lines = [];

        // Extract all <p>...</p> blocks
        preg_match_all('/<p.*?>(.*?)<\/p>/is', $html, $matches);

        foreach ($matches[1] as $pContent) {
            // Remove leading/trailing spaces
            $pContent = trim($pContent);

            // Handle <br> inside paragraphs
            $subLines = preg_split('/<br\s*\/?>/i', $pContent);

            foreach ($subLines as $subLine) {
                $line = trim(strip_tags($subLine));
                // If it was empty <p></p> or just <br>, keep as empty line
                $lines[] = $line;
            }
        }

        return $lines;
    }

    public function compare(string $baseText, string $modifiedText): array
    {
        $result = $this->lineDiffLCS($baseText, $modifiedText);
        logger("lineDiffLCS", $result);
        return $result;
    }


    public function reconstructFromDiffRanges(string $baseContent, array|string $diffData): string
    {
        if (is_string($diffData)) {
            $diffData = json_decode($diffData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                //         throw new \InvalidArgumentException("Invalid JSON diff data");
            }
        }

        // Split base content into lines
        $baseLines = $this->normalizeHtmlToLines($baseContent);

        // 1️⃣ Process deletions - from bottom up
        $deletions = $diffData['deletions'] ?? [];
        usort($deletions, fn($a, $b) => $b['line'] <=> $a['line']);

        foreach ($deletions as $del) {
            $startLine = max($del['line'] - 1, 0);
            array_splice($baseLines, $startLine, 1);
        }

        // 2️⃣ Process insertions - sort by line + relative_position
        $insertions = $diffData['insertions'] ?? [];
        usort($insertions, function ($a, $b) {
            if ($a['line'] === $b['line']) {
                return $a['relative_position'] <=> $b['relative_position'];
            }
            return $a['line'] <=> $b['line'];
        });

        foreach ($insertions as $ins) {
            $startLine = max($ins['line'] - 1, 0);
            array_splice($baseLines, $startLine, 0, [$ins['content']]);
        }

        // 3️⃣ Process modifications - replace whole lines
        $modifications = $diffData['modifications'] ?? [];
        foreach ($modifications as $mod) {
            $startLine = max($mod['line'] - 1, 0);
            $baseLines[$startLine] = $mod['modified'];
        }

        // 4️⃣ Convert lines back to HTML <p>
        return $this->linesToHtml($baseLines);
    }

    /**
     * Convert normalized lines back to HTML paragraphs.
     *
     * @param array $lines
     * @return string
     */
    public function linesToHtml(array $lines): string
    {
        $html = '';

        foreach ($lines as $line) {
            if ($line === '') {
                // Empty line: treat as <p><br/></p> to keep blank paragraph
                $html .= '<p><br/></p>';
            } elseif (str_contains($line, "\n")) {
                // Line has explicit \n: split into <br/>
                $html .= '<p>' . implode('<br/>', explode("\n", $line)) . '</p>';
            } else {
                // Normal single line
                $html .= '<p>' . e($line) . '</p>';
            }
        }

        return $html;
    }


}

