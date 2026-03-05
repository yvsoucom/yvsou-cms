<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\VerifyEmailController;

class VerifyEmailControllerTest extends TestCase
{
    protected $VerifyEmailController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->VerifyEmailController = new VerifyEmailController();
    }

    #[Test]
    public function test___invoke()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->VerifyEmailController->__invoke();
    }

}
