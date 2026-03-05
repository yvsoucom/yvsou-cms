<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\AutoUpdaterService;

class AutoUpdaterServiceTest extends TestCase
{
    protected $AutoUpdaterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->AutoUpdaterService = new AutoUpdaterService();
    }

    #[Test]
    public function test_checkLatestVersion()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AutoUpdaterService->checkLatestVersion();
    }

    #[Test]
    public function test_isOutdated()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AutoUpdaterService->isOutdated();
    }

    #[Test]
    public function test_downloadLatestZip()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AutoUpdaterService->downloadLatestZip();
    }

    #[Test]
    public function test_runPostUpdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AutoUpdaterService->runPostUpdate();
    }

    #[Test]
    public function test_applyUpdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AutoUpdaterService->applyUpdate();
    }

    #[Test]
    public function test_updateFromZip()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AutoUpdaterService->updateFromZip();
    }

}
