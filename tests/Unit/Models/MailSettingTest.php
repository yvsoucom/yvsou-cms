<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\MailSetting;

class MailSettingTest extends TestCase
{
    protected $MailSetting;

    protected function setUp(): void
    {
        parent::setUp();
        $this->MailSetting = new MailSetting();
    }

    #[Test]
    public function test_getSettings()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MailSetting->getSettings();
    }

    #[Test]
    public function test_updateSettings()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MailSetting->updateSettings();
    }

}
