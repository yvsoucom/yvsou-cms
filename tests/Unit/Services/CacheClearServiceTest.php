<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\CacheClearService;

class CacheClearServiceTest extends TestCase
{
    protected $CacheClearService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CacheClearService = new CacheClearService();
    }

    #[Test]
    public function test_clearCache()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CacheClearService->clearCache();
    }

}
