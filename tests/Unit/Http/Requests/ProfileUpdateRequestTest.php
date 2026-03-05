<?php

namespace Tests\Unit\Http\Requests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileUpdateRequestTest extends TestCase
{
    protected $ProfileUpdateRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ProfileUpdateRequest = new ProfileUpdateRequest();
    }

    #[Test]
    public function test_rules()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ProfileUpdateRequest->rules();
    }

}
