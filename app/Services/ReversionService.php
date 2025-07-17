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
use Symfony\Component\String\UnicodeString;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

// require 'vendor/autoload.php';
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
        $baseLines = $this->normalizeHtmlToLines($baseText);
        $modifiedLines = $this->normalizeHtmlToLines($modifiedText);

        $diff = $this->diffWithLineInfo($baseLines, $modifiedLines);
        logger("diffWithLineInfo", $diff);
        return $diff;
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
                $html .= '<p></p>';
            } else {
                // Normal single line
                $line = str_replace("\n", '', $line);
                $html .= '<p>' . e($line) . '</p>';
            }
        }

        return $html;
    }

    function diffWithLineInfo(array $baseLines, array $modifiedLines): array
    {
        $baseStr = implode("\n", $baseLines);
        $modStr = implode("\n", $modifiedLines);

        $outputBuilder = new StrictUnifiedDiffOutputBuilder([
            'fromFile' => 'base.txt',
            'toFile' => 'modified.txt',
            'collapseRanges' => false,
            'contextLines' => 0,
            'commonLineThreshold' => 1, // ✅ must be > 0
        ]);


        $differ = new Differ($outputBuilder);

        // diffToArray returns an array of [lineContent, lineType] pairs
        // lineType: 0 = unchanged, 1 = added, 2 = removed
        $diffArray = $differ->diffToArray($baseStr, $modStr);

        $result = [];
        $baseIndex = 0;
        $modIndex = 0;
        $insertRelativePos = [];

        foreach ($diffArray as $entry) {
            [$line, $tag] = $entry;

            if ($tag === 0) { // unchanged
                $result[] = [
                    'type' => 'unchanged',
                    'line' => $line,
                    'base_lineno' => $baseIndex,
                    'modified_lineno' => $modIndex,
                ];
                $baseIndex++;
                $modIndex++;
            } elseif ($tag === 1) { // added in modified
                // track relative insertion position after the last baseline line (baseIndex-1)
                $lastBaseLine = max($baseIndex - 1, 0);
                $insertRelativePos[$lastBaseLine] = ($insertRelativePos[$lastBaseLine] ?? 0) + 1;

                $result[] = [
                    'type' => 'inserted',
                    'line' => $line,
                    'relative_to' => $lastBaseLine,
                    'relative_position' => $insertRelativePos[$lastBaseLine],
                    'base_lineno' => null,
                    'modified_lineno' => $modIndex,
                ];
                $modIndex++;
            } elseif ($tag === 2) { // removed from base
                $result[] = [
                    'type' => 'deleted',
                    'line' => $line,
                    'base_lineno' => $baseIndex,
                    'modified_lineno' => null,
                ];
                $baseIndex++;
            }
        }

        // Detect modifications = a deleted + inserted pair at same base_lineno
        $final = [];
        for ($k = 0; $k < count($result); $k++) {
            $curr = $result[$k];
            if (
                $curr['type'] === 'deleted'
                && isset($result[$k + 1])
                && $result[$k + 1]['type'] === 'inserted'
                && $curr['base_lineno'] === $result[$k + 1]['relative_to']
            ) {
                $final[] = [
                    'type' => 'modified',
                    'baseline_lineno' => $curr['base_lineno'],
                    'base_line' => $curr['line'],
                    'line' => $result[$k + 1]['line'],
                    'relative_to' => $curr['base_lineno'],
                    'relative_position' => $result[$k + 1]['relative_position'],
                    'modified_lineno' => $result[$k + 1]['modified_lineno'],
                ];
                $k++; // skip next inserted line
            } else {
                // skip inserted lines already consumed in modification
                if (!($curr['type'] === 'inserted' && $k > 0 && $result[$k - 1]['type'] === 'deleted')) {
                    $final[] = $curr;
                }
            }
        }

        return $final;
    }
    function reconstructFromDiffRanges($baseText, string $diffjson)
    {
        $moditextlines = $this->reconstructModifiedFromDiff($baseText, $diffjson);
        logger("reconstructFromDiffRanges  ", $moditextlines);
        $modihtml = $this->linesToHtml($moditextlines);
        logger("reconstructFromDiffRanges  modihtml:", [$modihtml]);

        return $modihtml;
    }

    function reconstructModifiedFromDiff($baseText, string $diffjson): array
    {
        $diff = json_decode($diffjson, true);
        logger("reconstructModifiedFromDiff", $diff);

        $baseLines = $this->normalizeHtmlToLines($baseText);
        $res = [];

        foreach ($diff as $entry) {
            logger("reconstructModifiedFromDiff entry", $entry);
            switch ($entry['type']) {
                case 'inserted':
                case 'modified':
                    logger("reconstructModifiedFromDiff entry line", [$entry['line']]);

                    $res[] = $entry['line'];
                    logger("reconstructModifiedFromDiff entry res", $res);

                    break;

                case 'unchanged':
                    if (isset($entry['base_lineno']) && isset($baseLines[$entry['base_lineno']])) {
                        $res[] = $baseLines[$entry['base_lineno']];
                        logger("reconstructModifiedFromDiff entry res", $res);

                    } else {
                        logger()->error("Invalid base_lineno in diff", $entry);
                    }
                    break;

                // Optional: for robustness
                case 'deleted':
                    // Skip it – not part of reconstruction
                    break;

                default:
                    logger()->warning("Unknown diff entry type: " . $entry['type']);
            }
        }
        logger("reconstructModifiedFromDiff  res", $res);

        return $res;
    }


}

