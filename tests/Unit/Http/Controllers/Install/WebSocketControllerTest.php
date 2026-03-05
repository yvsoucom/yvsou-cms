<?php

namespace Tests\Unit\Http\Controllers\Install;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Install\WebSocketController;

class WebSocketControllerTest extends TestCase
{
    protected $WebSocketController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->WebSocketController = new WebSocketController();
    }

    #[Test]
    public function test_show()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WebSocketController->show();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->WebSocketController->store();
    }

}
