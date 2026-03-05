<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Console\Commands\StartWebSocket;

class StartWebSocketTest extends TestCase
{
    protected $StartWebSocket;

    protected function setUp(): void
    {
        parent::setUp();
        $this->StartWebSocket = new StartWebSocket();
    }

    #[Test]
    public function test_handle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->StartWebSocket->handle();
    }

}
