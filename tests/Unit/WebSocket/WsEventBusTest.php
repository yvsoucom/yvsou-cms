<?php

namespace Tests\Unit\WebSocket;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\WebSocket\WsEventBus;

class WsEventBusTest extends TestCase
{
    protected $WsEventBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->WsEventBus = new WsEventBus();
    }

    #[Test]
    public function test_on()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WsEventBus->on();
    }

    #[Test]
    public function test_dispatch()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WsEventBus->dispatch();
    }

}
