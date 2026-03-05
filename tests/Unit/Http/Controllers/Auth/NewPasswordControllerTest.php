<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\NewPasswordController;

class NewPasswordControllerTest extends TestCase
{
    protected $NewPasswordController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->NewPasswordController = new NewPasswordController();
    }

    #[Test]
    public function test_create()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->NewPasswordController->create();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->NewPasswordController->store();
    }

}
