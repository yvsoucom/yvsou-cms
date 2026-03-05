<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\WsPusher;

class WsPusherTest extends TestCase
{
    protected $WsPusher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->WsPusher = new WsPusher();
    }

    #[Test]
    public function test_sendToAll()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WsPusher->sendToAll();
    }

    #[Test]
    public function test_sendToUid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WsPusher->sendToUid();
    }

    #[Test]
    public function test_sendToGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WsPusher->sendToGroup();
    }

}
