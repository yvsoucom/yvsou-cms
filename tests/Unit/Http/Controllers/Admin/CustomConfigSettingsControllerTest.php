<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Admin\CustomConfigSettingsController;

class CustomConfigSettingsControllerTest extends TestCase
{
    protected $CustomConfigSettingsController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CustomConfigSettingsController = new CustomConfigSettingsController();
    }

    #[Test]
    public function test_edit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CustomConfigSettingsController->edit();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CustomConfigSettingsController->update();
    }

}
