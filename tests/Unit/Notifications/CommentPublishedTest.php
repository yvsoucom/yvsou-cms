<?php

namespace Tests\Unit\Notifications;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Notifications\CommentPublished;

class CommentPublishedTest extends TestCase
{
    protected $CommentPublished;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CommentPublished = new CommentPublished();
    }

    #[Test]
    public function test_via()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentPublished->via();
    }

    #[Test]
    public function test_toMail()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentPublished->toMail();
    }

    #[Test]
    public function test_toArray()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentPublished->toArray();
    }

}
