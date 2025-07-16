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

    function LineDiffWithBaseLineNumbers($baseText, $modifiedText)
    {
        // $baseLines = preg_split('/\R/', $baseText, -1, PREG_SPLIT_NO_EMPTY);
        // $modifiedLines = preg_split('/\R/', $modifiedText, -1, PREG_SPLIT_NO_EMPTY);
        $baseLines = $this->normalizeHtmlToLines($baseText);
        $modifiedLines = $this->normalizeHtmlToLines($modifiedText);


        // Create a map of base lines with their positions
        $baseLineMap = [];
        foreach ($baseLines as $lineNum => $line) {
            $baseLineMap[$line][] = $lineNum + 1; // Store 1-based line numbers
        }

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

            if ($baseLine === $modifiedLine) {
                $basePointer++;
                $modifiedPointer++;
            } else {
                $nextMatch = $this->findNextMatch($baseLines, $modifiedLines, $basePointer, $modifiedPointer);

                $baseGap = $nextMatch['basePos'] - $basePointer;
                $modGap = $nextMatch['modifiedPos'] - $modifiedPointer;

                if ($baseGap > 0 && $modGap > 0 && $baseGap === $modGap) {
                    for ($i = 0; $i < $baseGap; $i++) {
                        $baseLine = $baseLines[$basePointer + $i];
                        $modLine = $modifiedLines[$modifiedPointer + $i];
                        if ($baseLine !== $modLine) {

                            $result['modifications'][] = [
                                'line' => $basePointer + $i + 1,
                                'base' => $baseLine,
                                'modified' => $modLine,
                            ];

                        }
                    }
                } else {
                    for ($i = $basePointer; $i < $nextMatch['basePos']; $i++) {
                        $result['deletions'][] = [
                            'line' => $i + 1,
                            'content' => $baseLines[$i],
                        ];
                    }

                    for ($i = $modifiedPointer; $i < $nextMatch['modifiedPos']; $i++) {
                        $result['insertions'][] = [
                            'line' => $basePointer + 1,
                            'content' => $modifiedLines[$i],
                        ];
                    }
                }

                $basePointer = $nextMatch['basePos'];
                $modifiedPointer = $nextMatch['modifiedPos'];
            }
        }



        return $result;
    }

    private function findNextMatch(array $base, array $modified, int $basePos, int $modifiedPos): array
    {
        $baseLength = count($base);
        $modifiedLength = count($modified);

        // Create a map of modified lines for quick lookup
        $modifiedMap = [];
        for ($i = $modifiedPos; $i < $modifiedLength; $i++) {
            $line = $modified[$i];
            if (!isset($modifiedMap[$line])) {
                $modifiedMap[$line] = $i;
            }
        }

        // Find first line in base that exists in modified
        for ($i = $basePos; $i < $baseLength; $i++) {
            if (isset($modifiedMap[$base[$i]])) {
                return [
                    'basePos' => $i,
                    'modifiedPos' => $modifiedMap[$base[$i]]
                ];
            }
        }

        return [
            'basePos' => $baseLength,
            'modifiedPos' => $modifiedLength
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
        $result = $this->LineDiffWithBaseLineNumbers($baseText, $modifiedText);
        logger("LineDiffWithBaseLineNumbers", $result);
        return $result;
    }


    public function reconstructFromDiffRanges(string $baseContent, string $jsonDiff): string
    {
        $diffData = json_decode($jsonDiff, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Invalid JSON diff data");
        }

        // $baseLines = preg_split('/\R/u', $baseContent);
        $baseLines = $this->normalizeHtmlToLines($baseContent);
        $modifications = $diffData['modifications'] ?? [];
        usort($modifications, fn($a, $b) => $a['line'] <=> $b['line']);
        foreach ($modifications as $mod) {
            $startLine = $mod['line'];
            array_splice(
                $baseLines,
                $mod['line'],
                1,                     // remove 1 line at this index
                $mod['modified']       // this can be an array of new lines
            );
        }

        $deletions = $diffData['deletions'] ?? [];
        usort($deletions, fn($a, $b) => $b['line'] <=> $a['line']);
        foreach ($deletions as $del) {
            $startLine = max($del['line'] - 1, 0); // convert to 0-based index
            array_splice($baseLines, $startLine, 1);
        }

        $insertions = $diffData['insertions'] ?? [];
        usort($insertions, fn($a, $b) => $a['line'] <=> $b['line']);
        foreach ($insertions as $ins) {
            $startLine = max($ins['line'] - 1, 0);
            array_splice($baseLines, $startLine, 0, [$ins['content']]);
        }



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

