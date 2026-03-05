<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\HttpApiService;

class HttpApiServiceTest extends TestCase
{
    protected $HttpApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->HttpApiService = new HttpApiService();
    }

    #[Test]
    public function test_callRemote()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HttpApiService->callRemote();
    }

}
