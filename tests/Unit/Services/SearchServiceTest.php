<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\SearchService;

class SearchServiceTest extends TestCase
{
    protected $SearchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->SearchService = new SearchService();
    }

    #[Test]
    public function test_getKeywordPosts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getKeywordPosts();
    }

    #[Test]
    public function test_getMyKeywordPosts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getMyKeywordPosts();
    }

    #[Test]
    public function test_getpostfromkeys()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getpostfromkeys();
    }

    #[Test]
    public function test_getmypostfromkeys()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getmypostfromkeys();
    }

    #[Test]
    public function test_getDomainIdsFromDirkey()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getDomainIdsFromDirkey();
    }

    #[Test]
    public function test_getKeywordDirs()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getKeywordDirs();
    }

    #[Test]
    public function test_getMyALLDirs()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getMyALLDirs();
    }

    #[Test]
    public function test_getMyALLGroups()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchService->getMyALLGroups();
    }

}
