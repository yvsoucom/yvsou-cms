<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\WebSocketSupervisor;

class WebSocketSupervisorTest extends TestCase
{
    protected $WebSocketSupervisor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->WebSocketSupervisor = new WebSocketSupervisor();
    }

    #[Test]
    public function test_generateConfig()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WebSocketSupervisor->generateConfig();
    }

}
