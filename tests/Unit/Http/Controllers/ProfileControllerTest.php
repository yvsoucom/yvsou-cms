<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\ProfileController;

class ProfileControllerTest extends TestCase
{
    protected $ProfileController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ProfileController = new ProfileController();
    }

    #[Test]
    public function test_edit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ProfileController->edit();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ProfileController->update();
    }

    #[Test]
    public function test_destroy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ProfileController->destroy();
    }

}
