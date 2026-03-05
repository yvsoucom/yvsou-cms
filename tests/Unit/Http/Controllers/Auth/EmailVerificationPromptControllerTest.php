<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\EmailVerificationPromptController;

class EmailVerificationPromptControllerTest extends TestCase
{
    protected $EmailVerificationPromptController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->EmailVerificationPromptController = new EmailVerificationPromptController();
    }

    #[Test]
    public function test___invoke()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->EmailVerificationPromptController->__invoke();
    }

}
