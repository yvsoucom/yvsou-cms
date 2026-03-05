<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\PasswordController;

class PasswordControllerTest extends TestCase
{
    protected $PasswordController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PasswordController = new PasswordController();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PasswordController->update();
    }

}
