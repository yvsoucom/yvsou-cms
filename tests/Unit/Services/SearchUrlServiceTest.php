<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\SearchUrlService;

class SearchUrlServiceTest extends TestCase
{
    protected $SearchUrlService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->SearchUrlService = new SearchUrlService();
    }

    #[Test]
    public function test_searchMyKeywordRecord()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchUrlService->searchMyKeywordRecord();
    }

    #[Test]
    public function test_searchKeywordRecord()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchUrlService->searchKeywordRecord();
    }

    #[Test]
    public function test_updateMyKeywordRecord()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchUrlService->updateMyKeywordRecord();
    }

    #[Test]
    public function test_updateKeywordRecord()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchUrlService->updateKeywordRecord();
    }

    #[Test]
    public function test_updateDirsRecord()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchUrlService->updateDirsRecord();
    }

    #[Test]
    public function test_searchDirRecord()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchUrlService->searchDirRecord();
    }

}
