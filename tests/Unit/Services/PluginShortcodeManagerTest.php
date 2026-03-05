<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\PluginShortcodeManager;

class PluginShortcodeManagerTest extends TestCase
{
    protected $PluginShortcodeManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PluginShortcodeManager = new PluginShortcodeManager();
    }

    #[Test]
    public function test_getManager()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginShortcodeManager->getManager();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginShortcodeManager->render();
    }

}
