<?php
/**
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-07-15
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
 
 
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ReversionService;

class ReversionServiceTest extends TestCase
{
    private ReversionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReversionService();
    }

    private function extractWords(array $wordArrays): array
    {
        return array_map(fn($w) => $w, $wordArrays); // words are simple strings in your new diff structure
    }

    /** @test */
    public function it_generates_word_level_diff_and_reconstructs_correctly()
    {
        $base = "<p>test history version</p>";
        $modified = "<p>add one test history version add end!</p>";

        $diff = $this->service->compare($base, $modified);

        $this->assertNotEmpty($diff['insertions']);
        $this->assertCount(2, $diff['insertions']);

        // First insertion words
        $this->assertSame(
            ['add', 'one'],
            $diff['insertions'][0]['words']
        );

        // Second insertion words
        $this->assertSame(
            ['add', 'end!'],
            $diff['insertions'][1]['words']
        );

        $jsonDiff = json_encode($diff);

        $reconstructed = $this->service->reconstructFromDiffRanges($base, $jsonDiff);

        // Normalize whitespace for a more robust comparison
        $this->assertEquals(
            trim(preg_replace('/\s+/', ' ', $modified)),
            trim(preg_replace('/\s+/', ' ', $reconstructed)),
            "Reconstructed content should match modified content"
        );
    }

    /** @test */
    public function it_handles_word_deletions_correctly()
    {
        $base = "This is a sentence to delete.";
        $modified = "This is a to delete.";

        $diff = $this->service->compare($base, $modified);

        $this->assertNotEmpty($diff['deletions']);

        // Extract deleted words for first deletion range
        $deletedWords = $diff['deletions'][0]['words'];

        $this->assertSame(['sentence'], $deletedWords);

        $jsonDiff = json_encode($diff);
        $reconstructed = $this->service->reconstructFromDiffRanges($base, $jsonDiff);

        $this->assertEquals(
            trim(preg_replace('/\s+/', ' ', $modified)),
            trim(preg_replace('/\s+/', ' ', $reconstructed)),
            "Reconstructed content should match modified content after deletion"
        );
    }

    /** @test */
    public function it_handles_insertions_and_deletions_together()
    {
        $base = "The quick brown fox jumps over the lazy dog.";
        $modified = "The quick fox jumps over the dog.";

        $diff = $this->service->compare($base, $modified);

        $this->assertNotEmpty($diff['deletions']);

        // Collect all deleted words across deletion ranges
        $deletedWords = [];
        foreach ($diff['deletions'] as $del) {
            $deletedWords = array_merge($deletedWords, $del['words']);
        }

        $this->assertContains('brown', $deletedWords);
        $this->assertContains('lazy', $deletedWords);

        $jsonDiff = json_encode($diff);
        $reconstructed = $this->service->reconstructFromDiffRanges($base, $jsonDiff);

        $this->assertEquals(
            trim(preg_replace('/\s+/', ' ', $modified)),
            trim(preg_replace('/\s+/', ' ', $reconstructed)),
            "Reconstructed content should match modified content after insertions and deletions"
        );
    }
}
  