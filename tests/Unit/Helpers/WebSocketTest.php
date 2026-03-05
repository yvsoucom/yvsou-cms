<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Helpers\WebSocket;

class WebSocketTest extends TestCase
{
    protected $WebSocket;

    protected function setUp(): void
    {
        parent::setUp();
        $this->WebSocket = new WebSocket();
    }

    #[Test]
    public function test_send()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WebSocket->send();
    }

}
