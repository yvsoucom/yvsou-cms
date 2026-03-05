<?php

namespace Tests\Unit\Http\Controllers\Websocket;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Websocket\ExternalApiController;

class ExternalApiControllerTest extends TestCase
{
    protected $ExternalApiController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ExternalApiController = new ExternalApiController();
    }

    #[Test]
    public function test_remoteCall()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ExternalApiController->remoteCall();
    }

}
