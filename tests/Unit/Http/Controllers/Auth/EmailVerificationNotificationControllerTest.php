<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

class EmailVerificationNotificationControllerTest extends TestCase
{
    protected $EmailVerificationNotificationController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->EmailVerificationNotificationController = new EmailVerificationNotificationController();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->EmailVerificationNotificationController->store();
    }

}
