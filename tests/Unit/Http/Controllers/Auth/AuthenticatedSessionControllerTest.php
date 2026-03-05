<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

class AuthenticatedSessionControllerTest extends TestCase
{
    protected $AuthenticatedSessionController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->AuthenticatedSessionController = new AuthenticatedSessionController();
    }

    #[Test]
    public function test_create()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AuthenticatedSessionController->create();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AuthenticatedSessionController->store();
    }

    #[Test]
    public function test_destroy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AuthenticatedSessionController->destroy();
    }

}
