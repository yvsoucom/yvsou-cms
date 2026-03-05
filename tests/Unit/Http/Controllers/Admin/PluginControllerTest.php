<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Admin\PluginController;

class PluginControllerTest extends TestCase
{
    protected $PluginController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PluginController = new PluginController();
    }

    #[Test]
    public function test_index()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginController->index();
    }

    #[Test]
    public function test_toggle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginController->toggle();
    }

    #[Test]
    public function test_switch()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginController->switch();
    }

    #[Test]
    public function test_destroy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginController->destroy();
    }

    #[Test]
    public function test_replaceMigrationPrefix()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginController->replaceMigrationPrefix();
    }

    #[Test]
    public function test_upload()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginController->upload();
    }

}
