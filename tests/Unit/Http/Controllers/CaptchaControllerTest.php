<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\CaptchaController;

class CaptchaControllerTest extends TestCase
{
    protected $CaptchaController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CaptchaController = new CaptchaController();
    }

    #[Test]
    public function test_show()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CaptchaController->show();
    }

    #[Test]
    public function test_verify()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CaptchaController->verify();
    }

}
