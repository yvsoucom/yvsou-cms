<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\ShortcodeManager;

class ShortcodeManagerTest extends TestCase
{
    protected $ShortcodeManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ShortcodeManager = new ShortcodeManager();
    }

    #[Test]
    public function test_register()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeManager->register();
    }

    #[Test]
    public function test_hasShortcode()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeManager->hasShortcode();
    }

    #[Test]
    public function test_getShortcodeHandler()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeManager->getShortcodeHandler();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeManager->render();
    }

    #[Test]
    public function test_process()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeManager->process();
    }

}
