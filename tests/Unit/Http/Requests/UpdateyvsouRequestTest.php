<?php

namespace Tests\Unit\Http\Requests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\UpdateyvsouRequest;

class UpdateyvsouRequestTest extends TestCase
{
    protected $UpdateyvsouRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UpdateyvsouRequest = new UpdateyvsouRequest();
    }

    #[Test]
    public function test_authorize()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->UpdateyvsouRequest->authorize();
    }

    #[Test]
    public function test_rules()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->UpdateyvsouRequest->rules();
    }

}
