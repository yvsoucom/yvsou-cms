<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\RegisteredUserController;

class RegisteredUserControllerTest extends TestCase
{
    protected $RegisteredUserController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->RegisteredUserController = new RegisteredUserController();
    }

    #[Test]
    public function test_create()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RegisteredUserController->create();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RegisteredUserController->store();
    }

}
