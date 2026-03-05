<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Console\Commands\WebSocketCommand;

class WebSocketCommandTest extends TestCase
{
    protected $WebSocketCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->WebSocketCommand = new WebSocketCommand();
    }

    #[Test]
    public function test_handle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WebSocketCommand->handle();
    }

}
