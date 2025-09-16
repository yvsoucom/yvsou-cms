<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
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
  