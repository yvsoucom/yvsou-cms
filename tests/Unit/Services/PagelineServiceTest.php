<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\PagelineService;

class PagelineServiceTest extends TestCase
{
    protected $PagelineService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PagelineService = new PagelineService();
    }

    #[Test]
    public function test_showNewPosts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PagelineService->showNewPosts();
    }

    #[Test]
    public function test_getPagelines()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PagelineService->getPagelines();
    }

    #[Test]
    public function test_showNewDirs()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PagelineService->showNewDirs();
    }

}
