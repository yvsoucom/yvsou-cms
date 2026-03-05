<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\FilterManager;

class FilterManagerTest extends TestCase
{
    protected $FilterManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->FilterManager = new FilterManager();
    }

    #[Test]
    public function test_addFilter()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FilterManager->addFilter();
    }

    #[Test]
    public function test_applyFilters()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FilterManager->applyFilters();
    }

}
