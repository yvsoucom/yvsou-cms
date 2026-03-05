<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\MessageService;

class MessageServiceTest extends TestCase
{
    protected $MessageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->MessageService = new MessageService();
    }

    #[Test]
    public function test_send()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MessageService->send();
    }

    #[Test]
    public function test_fetchUnread()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MessageService->fetchUnread();
    }

    #[Test]
    public function test_markAsRead()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->MessageService->markAsRead();
    }

}
