<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\PluginFilterManager;

class PluginFilterManagerTest extends TestCase
{
    protected $PluginFilterManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PluginFilterManager = new PluginFilterManager();
    }

    #[Test]
    public function test_getManager()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginFilterManager->getManager();
    }

}
