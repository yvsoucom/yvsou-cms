<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Admin\MailSettingsController;

class MailSettingsControllerTest extends TestCase
{
    protected $MailSettingsController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->MailSettingsController = new MailSettingsController();
    }

    #[Test]
    public function test_edit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MailSettingsController->edit();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MailSettingsController->update();
    }

}
