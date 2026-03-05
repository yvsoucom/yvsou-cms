<?php

namespace Tests\Unit\Http\Controllers\Msg;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Msg\MessageController;

class MessageControllerTest extends TestCase
{
    protected $MessageController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->MessageController = new MessageController();
    }

    #[Test]
    public function test_showMessages()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MessageController->showMessages();
    }

}
