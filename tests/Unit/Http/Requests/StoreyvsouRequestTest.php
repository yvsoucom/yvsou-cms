<?php

namespace Tests\Unit\Http\Requests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\StoreyvsouRequest;

class StoreyvsouRequestTest extends TestCase
{
    protected $StoreyvsouRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->StoreyvsouRequest = new StoreyvsouRequest();
    }

    #[Test]
    public function test_authorize()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->StoreyvsouRequest->authorize();
    }

    #[Test]
    public function test_rules()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->StoreyvsouRequest->rules();
    }

}
