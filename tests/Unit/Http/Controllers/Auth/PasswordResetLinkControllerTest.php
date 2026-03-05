<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\PasswordResetLinkController;

class PasswordResetLinkControllerTest extends TestCase
{
    protected $PasswordResetLinkController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PasswordResetLinkController = new PasswordResetLinkController();
    }

    #[Test]
    public function test_create()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PasswordResetLinkController->create();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PasswordResetLinkController->store();
    }

}
