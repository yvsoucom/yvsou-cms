<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\ReversionService;

class ReversionServiceTest extends TestCase
{
    protected $ReversionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ReversionService = new ReversionService();
    }

    #[Test]
    public function test_reconstructHtmlPostVersion()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->reconstructHtmlPostVersion();
    }

    #[Test]
    public function test_generateCompareJson()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->generateCompareJson();
    }

    #[Test]
    public function test_normalizeHtmlToLines()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->normalizeHtmlToLines();
    }

    #[Test]
    public function test_compare()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->compare();
    }

    #[Test]
    public function test_linesToHtml()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->linesToHtml();
    }

    #[Test]
    public function test_diffWithLineInfo()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->diffWithLineInfo();
    }

    #[Test]
    public function test_reconstructFromDiffRanges()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->reconstructFromDiffRanges();
    }

    #[Test]
    public function test_reconstructModifiedFromDiff()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ReversionService->reconstructModifiedFromDiff();
    }

}
