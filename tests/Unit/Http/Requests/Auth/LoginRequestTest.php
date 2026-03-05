<?php

namespace Tests\Unit\Http\Requests\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\Auth\LoginRequest;

class LoginRequestTest extends TestCase
{
    protected $LoginRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->LoginRequest = new LoginRequest();
    }

    #[Test]
    public function test_authorize()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LoginRequest->authorize();
    }

    #[Test]
    public function test_rules()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LoginRequest->rules();
    }

    #[Test]
    public function test_authenticate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LoginRequest->authenticate();
    }

    #[Test]
    public function test_ensureIsNotRateLimited()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LoginRequest->ensureIsNotRateLimited();
    }

    #[Test]
    public function test_throttleKey()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LoginRequest->throttleKey();
    }

}
